<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Influencer;
use App\Models\LiveClass;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\Video;
use App\Models\Batch;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //Dashboard Home

    public function dashboardHome()
    {
        $students = Student::count();
        $lastMonthStudents = Student::whereMonth('created_at', Carbon::now()->subMonth()->month)
                            ->whereYear('created_at', Carbon::now()->subMonth()->year)
                            ->count();
        $studentsChange = $this->calculatePercentageChange($students, $lastMonthStudents);

        $activeStudents = Student::where('status', 1)->count();

        $activeEnrollments = StudentEnrollment::where('status', 'active')->count();
        $lastMonthActive = StudentEnrollment::where('status', 'active')
                            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                            ->whereYear('created_at', Carbon::now()->subMonth()->year)
                            ->count();
        $activeEnrollmentsChange = $this->calculatePercentageChange($activeEnrollments, $lastMonthActive);

        $newEnrollments = StudentEnrollment::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();
        $lastMonthNewEnrollments = StudentEnrollment::whereMonth('created_at', Carbon::now()->subMonth()->month)
                                                    ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                                    ->count();
        $newEnrollmentsChange = $this->calculatePercentageChange($newEnrollments, $lastMonthNewEnrollments);

        $cancelledEnrollments = StudentEnrollment::where('status', 'cancelled')->count();
        $lastMonthCancelled = StudentEnrollment::where('status', 'cancelled')
                            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                            ->whereYear('created_at', Carbon::now()->subMonth()->year)
                            ->count();
        $cancelledEnrollmentsChange = $this->calculatePercentageChange($cancelledEnrollments, $lastMonthCancelled);
        $monthlyEnrollments = StudentEnrollment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                            ->whereYear('created_at', Carbon::now()->year)
                            ->groupBy(DB::raw('MONTH(created_at)'))
                            ->orderBy('month')
                            ->pluck('count', 'month')
                            ->toArray();

                            // Fill months that have 0 enrollments
                            $allMonths = [];
                            foreach (range(1, 12) as $m) {
                                $allMonths[] = $monthlyEnrollments[$m] ?? 0;
                            }
        $monthlyLiveClassAttendance = DB::table('live_class_participants')
                                    ->selectRaw('MONTH(join_time) as month, COUNT(*) as count')
                                    ->whereYear('join_time', Carbon::now()->year)
                                    ->groupBy(DB::raw('MONTH(join_time)'))
                                    ->orderBy('month')
                                    ->pluck('count', 'month')
                                    ->toArray();

        // Fill months with 0 if no attendance
        $attendanceTrends = [];
        foreach (range(1, 12) as $m) {
            $attendanceTrends[] = $monthlyLiveClassAttendance[$m] ?? 0;
        }
        // Exam Participation & Pass Rate
        $passThreshold = 40;

        $totalParticipants = DB::table('exam_participants')->distinct('student_id')->count('student_id');
        $totalScored = DB::table('student_scores')->distinct('student_id')->count('student_id');
        $totalPassed = DB::table('student_scores')->where('total_score', '>=', $passThreshold)->distinct('student_id')->count('student_id');
        $totalFailed = $totalScored - $totalPassed;
        $totalNotAttempted = $totalParticipants - $totalScored;

        $examStats = [
            'passed'         => $totalPassed,
            'failed'         => max($totalFailed, 0),
            'not_attempted'  => max($totalNotAttempted, 0),
        ];

        $topStudents = DB::table('student_scores')
            ->join('students', 'student_scores.student_id', '=', 'students.id')
            ->select(
                'students.first_name',
                'students.last_name',
                'students.admission_number',
                DB::raw('AVG(student_scores.total_score) as avg_score'),
                DB::raw('COUNT(student_scores.exam_id) as exams_taken')
            )
            ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.admission_number')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();
        
            $coursePerformance = DB::table('courses')
                ->where('courses.type', 'lms')
                ->leftJoin('student_enrollments', 'courses.id', '=', 'student_enrollments.course_id')
                ->leftJoin('student_scores', function ($join) {
                    $join->on('student_enrollments.student_id', '=', 'student_scores.student_id');
                })
                ->select(
                    'courses.name as course_name',
                    DB::raw('COUNT(DISTINCT student_enrollments.student_id) as total_enrolled'),
                    DB::raw('COUNT(CASE WHEN student_enrollments.status = "completed" THEN 1 END) as completed_count'),
                    DB::raw('ROUND(AVG(student_scores.total_score), 2) as avg_score')
                )
                ->groupBy('courses.id', 'courses.name')
                ->get();


        $recentEnrollments = DB::table('student_enrollments')
                            ->join('students', 'student_enrollments.student_id', '=', 'students.id')
                            ->join('courses', 'student_enrollments.course_id', '=', 'courses.id')
                            ->join('users', 'students.user_id', '=', 'users.id')
                            ->select(
                                'students.first_name',
                                'students.last_name',
                                'courses.name as course_name',
                                'student_enrollments.created_at'
                            )
                            ->orderBy('student_enrollments.created_at', 'desc')
                            ->limit(5)
                            ->get();
        $recentCompletedExams = DB::table('exams')
                                ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
                                ->select(
                                    'exams.name as exam_name',
                                    'subjects.name as subject_name',
                                    'exams.updated_at'
                                    )
                                    ->where('exams.status', 'Completed')
                                    ->orderBy('exams.updated_at', 'desc')
                                    ->limit(5)
                                    ->get();

        $videoStats = DB::table('videos')
                    ->leftJoin('user_video_tracks', 'videos.id', '=', 'user_video_tracks.video_id')
                    ->select(
                        'videos.id',
                        'videos.title',
                        DB::raw("SUM(CASE WHEN user_video_tracks.video_status = 'completed' THEN 1 ELSE 0 END) as completed_count"),
                        DB::raw("COUNT(user_video_tracks.id) as total_views")
                    )
                    ->groupBy('videos.id', 'videos.title')
                    ->orderBy('videos.id', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($video) {
                        $video->completion_rate = $video->total_views > 0
                            ? round(($video->completed_count / $video->total_views) * 100, 1)
                            : 0;
                        return $video;
                    });
        $batches = Batch::count();

        $data = [
            'students'                 => $students,
            'studentsChange'           => $studentsChange,
            'activeStudents'           => $activeStudents,
            'activeEnrollments'        => $activeEnrollments,
            'activeEnrollmentsChange'  => $activeEnrollmentsChange,
            'newEnrollments'           => $newEnrollments,
            'newEnrollmentsChange'     => $newEnrollmentsChange,
            'cancelledEnrollments'     => $cancelledEnrollments,
            'cancelledEnrollmentsChange' => $cancelledEnrollmentsChange,
            'total_course'             => Course::where('type', 'lms')->count(),
            'total_subjects'           => Subject::where('type', 'lms')->count(),
            'live_classes'             => LiveClass::count(),
            'total_exams'              => Exam::count(),
            'total_videos'             => Video::count(),
            'influencers'              => Influencer::count(),
            'monthlyEnrollments'       => $allMonths,
            'attendanceTrends'         => $attendanceTrends,
            'examStats'                => $examStats,
            'topStudents'              => $topStudents,
            'coursePerformance'        => $coursePerformance,
            'recentEnrollments'        => $recentEnrollments,
            'recentCompletedExams'     => $recentCompletedExams,
            'videoStats'               => $videoStats,
            'batches'                  => $batches,
        ];

        extract($data);

        return view('lms.sections.dashboard', $data);
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    public function analytics() {
        return view('lms.sections.analytics'); 
    }
}
