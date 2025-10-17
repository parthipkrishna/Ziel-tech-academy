<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Exam;
use App\Models\StudentScore;
use App\Models\Subscription;
use App\Models\UserVideoTrack;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $courseIds = Subscription::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('course_id');

        // Load subjects with relationships
        $subjects = Subject::with(['sessions.videos'])
            ->whereIn('course_id', $courseIds)
            ->get()
            ->map(function ($subject) use ($student) {
                // Use the new calculation method
                [$subjectTotal, $subjectCompleted, $counts] =
                    $this->calculateSubjectProgress($student->id, $subject);

                $subject->total_items       = $subjectTotal;
                $subject->completed_items   = $subjectCompleted;
                $subject->progress_percent  = $subjectTotal > 0
                    ? round(($subjectCompleted / $subjectTotal) * 100, 2)
                    : 0;
                $subject->breakdown         = $counts;

                return $subject;
            });

        // Attendance & Quality check (existing code)
        $attendance = $this->getAttendance($student->id, $request->get('attendance', 'week'));
        $syllabus = [
            'subjects_progress' => $subjects->map(fn($s) => [
                'breakdown' => $s->breakdown
            ])->toArray()
        ];
        $qualityCheck = $this->getQualityCheck($student->id, $syllabus, $attendance['days']);

        // Fetch syllabus tracker (overall donut)
        $syllabusTracker = [];
        foreach ($courseIds as $courseId) {
            $syllabusTracker[] = $this->getSyllabusTracker($student->id, $courseId);
        }

        return view('student.analytics.dashboard-analytics', compact(
            'subjects',
            'attendance',
            'qualityCheck',
            'syllabusTracker'
        ));
    }

    private function getAttendance(int $studentId, ?string $filter = 'week'): array
    {
        $range = match ($filter) {
            'month'    => 30,
            '3months'  => 90,
            default    => 7,
        };

        $startDate = Carbon::now()->subDays($range - 1)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        $attendanceLogs = Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $days = [];
        $presentCount = 0;

        for ($i = 0; $i < $range; $i++) {
            $day = Carbon::now()->subDays($range - 1 - $i)->format('Y-m-d');
            $isPresent = in_array($day, $attendanceLogs);

            if ($isPresent) $presentCount++;

            $days[] = [
                'day'        => $i + 1,
                'date'       => $day,
                'is_present' => $isPresent,
            ];
        }

        $percentage = round(($presentCount / $range) * 100, 2);

        $status = match (true) {
            $percentage >= 50 => "🎉 Great! You are going well at the moment",
            $percentage >= 20 => "⚠️ Needs Improvement! Try to attend more classes",
            default           => "❌ Critical! Your attendance is too low",
        };

        return compact('days', 'percentage', 'status');
    }

    private function getQualityCheck(int $studentId, array $syllabus, array $attendanceDays): array
    {
        $totals = collect($syllabus['subjects_progress'] ?? [])
            ->pluck('breakdown')
            ->reduce(function ($carry, $item) {
                foreach ($item as $key => $val) {
                    $carry[$key] = ($carry[$key] ?? 0) + $val;
                }
                return $carry;
            }, []);

        $totalDays = count($attendanceDays);
        $presentDays = collect($attendanceDays)->where('is_present', true)->count();
        $attendancePercent = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;

        return [
            'live_classes'    => ($totals['live_classes'] ?? 0) > 0 ? 'Pass' : 'Fail',
            'assessments'     => ($totals['assessments'] ?? 0) > 0 ? 'Pass' : 'Fail',
            'recorded_videos' => ($totals['videos'] ?? 0) > 0 ? 'Pass' : 'Fail',
            'attendance'      => $attendancePercent >= 50 ? 'Pass' : 'Fail',
        ];
    }

    private function getSyllabusTracker(int $studentId, int $courseId, ?int $subjectId = null): array
    {
        $course = Course::with(['subjects.sessions.videos', 'subjects.liveClasses'])
            ->find($courseId);

        if (!$course) return ['status' => false, 'error' => 'Course not found'];

        $subjectsQuery = $course->subjects();
        if ($subjectId) $subjectsQuery->where('id', $subjectId);

        $subjects = $subjectsQuery->get();

        $syllabus = [];
        $totalItems = $totalCompleted = 0;

        foreach ($subjects as $subject) {
            [$subjectTotal, $subjectCompleted, $counts] =
                $this->calculateSubjectProgress($studentId, $subject);

            $completionPercent = $subjectTotal > 0
                ? round(($subjectCompleted / $subjectTotal) * 100, 2)
                : 0;

            $syllabus[] = [
                'subject_id'         => $subject->id,
                'subject_name'       => $subject->name,
                'total_items'        => $subjectTotal,
                'completed_items'    => $subjectCompleted,
                'completion_percent' => $completionPercent,
                'breakdown'          => $counts,
            ];

            $totalItems     += $subjectTotal;
            $totalCompleted += $subjectCompleted;
        }

        $courseProgress = $totalItems > 0 ? round(($totalCompleted / $totalItems) * 100, 2) : 0;

        return [
            'course_id'        => $course->id,
            'course_title'     => $course->name,
            'total_progress'   => $courseProgress,
            'subjects_progress'=> $syllabus,
        ];
    }

    private function calculateSubjectProgress(int $studentId, Subject $subject): array
    {
        $subjectTotal = $subjectCompleted = 0;
        $counts = ['videos' => 0, 'assessments' => 0, 'exams' => 0, 'live_classes' => 0];

        // 📹 Videos via sessions
        foreach ($subject->sessions as $session) {
            $videoIds = $session->videos->pluck('id');
            $subjectTotal += $videoIds->count();
            $counts['videos'] += $videoIds->count();

            $completedVideos = VideoProgress::where('student_id', $studentId)
                ->whereIn('video_id', $videoIds)
                ->where('is_completed', 1)
                ->count();
            $subjectCompleted += $completedVideos;
        }

        // 📑 Assessments & Exams
        foreach (['Assessment', 'Exam'] as $type) {
            $exams = Exam::where('subject_id', $subject->id)
                ->where('type', $type)
                ->get();

            foreach ($exams as $exam) {
                $subjectTotal++;
                $counts[strtolower($type) . 's']++;

                $score = StudentScore::where('student_id', $studentId)
                    ->where('exam_id', $exam->id)
                    ->first();

                if ($score?->total_score >= $exam->minimum_passing_marks) {
                    $subjectCompleted++;
                }
            }
        }

        return [$subjectTotal, $subjectCompleted, $counts];
    }
}

