<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Course;
use App\Models\Exam;
use App\Models\LiveClass;
use App\Models\StudentEnrollment;
use App\Models\StudentScore;
use App\Models\ToolKit;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\UserVideoTrack;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();

            $courseIds = Subscription::where('student_id', $student->id)
                    ->where('status', 'active')
                    ->pluck('course_id');

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

            $live_class = LiveClass::with(['subject', 'participants', 'tutor.user'])
                ->where('status', ['Ongoing', 'Pending']) 
                ->where('start_time', '>=', Carbon::today())
                ->latest()
                ->first();

            $all_live_class = LiveClass::with('participants')
                ->where('status', ['Ongoing', 'Pending'])
                ->where('start_time', '>=', Carbon::today())
                ->orderBy('start_time', 'desc')
                ->get();


           $videoHistory = UserVideoTrack::with('video')
                ->where('user_id', $user->id)
                ->orderBy('last_watched_at', 'desc')
                ->first();

            $courses = Course::where('status', 1)
                    ->where('type', 'lms')
                    ->take(3)
                    ->get();
            $banner = Banner::where('status', 1)
                    ->with(['toolkit', 'course'])
                    ->where(function ($query) use ($courseIds) {
                        $query->where('type', '!=', 'course')
                            ->orWhereNotIn('related_id', $courseIds);
                    })
                    ->get();

            $data = [
                'subjects' => $subjects,
                'live_class' => $live_class,
                'videoHistory' => $videoHistory,
                'courses' => $courses,
                'all_live_classes' =>$all_live_class,
                'banner' => $banner,
            ];

            return view('student.home.student-home', compact('data'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch subjects: ' . $e->getMessage());
        }
    }

    public function showCertificates()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $enrollments = StudentEnrollment::with('course')
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->get();

        return view('student.certificate.index', compact('student', 'enrollments'));
    }

    public function ToolkitDetails($id)
    {
        $toolkit = ToolKit::with('media')->find($id);

        if (!$toolkit) {
            return response()->json(null, 404);
        }

        return response()->json([
            'id' => $toolkit->id,
            'name' => $toolkit->name,
            'short_description' => $toolkit->short_description,
            'price' => $toolkit->price,
            'offer_price' => $toolkit->offer_price,
            'media' => $toolkit->media->map(function($m) {
                return [
                    'id' => $m->id,
                    'file_path' => asset('storage/' . str_replace('public/', '', $m->file_path)),
                ];
            }),
        ]);
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
