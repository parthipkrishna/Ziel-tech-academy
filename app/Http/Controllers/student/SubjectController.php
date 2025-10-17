<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\RecordedVideo;
use App\Models\StudentProgress;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\VideoProgress;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    public function index($courseId = null)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $studentId = $student->id;

            $query = Subject::where('type', 'lms')
                ->with(['subjectSessions.videos' => function ($query) {
                    $query->orderBy('created_at');
                }, 'subjectSessions.assessments']);

            if ($courseId) {
                $isSubscribed = Subscription::where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->where('status', 'active')
                    ->exists();

                if (!$isSubscribed) {
                    return redirect()->back()->with('error', 'You are not subscribed to this course.');
                }
                $subjects = $query->where('course_id', $courseId)->orderBy('created_at')->get(); // Ensure subjects are ordered
            } else {
                $subscribedCourseIds = Subscription::where('student_id', $studentId)
                    ->where('status', 'active')
                    ->pluck('course_id');
                $subjects = $query->whereIn('course_id', $subscribedCourseIds)->orderBy('created_at')->get(); // Ensure subjects are ordered
            }

            $isContentUnlocked = true;

            foreach ($subjects as $subject) {
                $sessions = $subject->subjectSessions->sortBy('created_at'); 
                $firstSessionId = $sessions->first()?->id;

                foreach ($sessions as $session) {
                    $session->setStudentId($studentId);
                     // If this is the first session, force unlock
                    $forceUnlock = $session->id === $firstSessionId;

                    $videos = $session->videos->sortBy('created_at')->values();

                    foreach ($videos as $video) {
                        $isCompleted = VideoProgress::where('student_id', $studentId)
                            ->where('video_id', $video->id)
                            ->where('is_completed', true)
                            ->exists();

                         $video->is_locked = $forceUnlock ? false : !$isContentUnlocked;

                        if ($isContentUnlocked && !$isCompleted && !$forceUnlock) {
                            $isContentUnlocked = false;
                        }
                    }

                    foreach ($session->assessments as $assessment) {
                        $assessment->is_locked = $forceUnlock ? false : !$isContentUnlocked;
                        $assessment->total_sessions = RecordedVideo::
                                where('subject_session_id', $session->id)
                                ->where('is_enabled', true)
                                ->count();
                                    }
                }

                $finalExam = Exam::where('subject_id', $subject->id)
                    ->where('type', 'Exam')
                    ->first();

                if ($finalExam) {
                    $completedSessionsCount = StudentProgress::where('student_id', $studentId)
                        ->where('subject_id', $subject->id)
                        ->where('status', 'completed')
                        ->count();

                    $finalExam->is_locked = $completedSessionsCount < $sessions->count();
                    $finalExam->total_sessions = RecordedVideo::where('subject_id', $subject->id)
                        ->where('is_enabled', true)
                        ->count();
                }
                $subject->final_exam = $finalExam;
            }

            return view('student.subjects.subject', compact('subjects'));

        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Student or subject not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load subjects: ' . $e->getMessage());
        }
    }
}
