<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\RecordedVideo;
use App\Models\StudentProgress;
use App\Models\Subject;
use App\Models\UserVideoTrack;
use App\Models\VideoLog;
use App\Models\VideoProgress;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subscription;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
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

            return view('student.videos.index', compact('subjects'));

        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Student or subject not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load subjects: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'video_status' => 'required|in:in_progress,completed,paused,locked',
            'seek_position' => 'nullable|integer',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $track = UserVideoTrack::updateOrCreate(
            [
                'user_id' => $user->id,
                'video_id' => $request->video_id
            ],
            [
                'video_status' => $request->video_status,
                'seek_position' => $request->seek_position,
                'last_watched_at' => now(),
                'paused_at' => $request->video_status === 'paused' ? now() : null
            ]
        );

        return response()->json(['message' => 'Video status updated', 'track' => $track]);
    }
    
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer|exists:videos,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'subject_session_id' => 'required|integer|exists:subject_sessions,id',
            'currentTime' => 'required|numeric',
            'event' => 'required|string|in:start,update,left,completed',
        ]);

        try {
            $student = Student::where('user_id', Auth::id())->firstOrFail();
            $studentId = $student->id;
            $currentTime = floor($request->currentTime);

            // Find the most recent 'watching' log for this student and video
            $log = VideoLog::where('student_id', $studentId)
                ->where('video_id', $request->video_id)
                ->where('status', 'watching')
                ->latest()
                ->first();
            
            $duration = 0; // Initialize duration

            if ($request->event === 'start' && !$log) {
                // Create a new log entry when the video starts
                VideoLog::create([
                    'student_id' => $studentId,
                    'video_id' => $request->video_id,
                    'subject_id' => $request->subject_id,
                    'subject_session_id' => $request->subject_session_id,
                    'start_time' => $currentTime,
                    'end_time' => $currentTime,
                    'duration' => 0,
                    'status' => 'watching',
                ]);
                $duration = 0; // No duration to add on 'start'
            } elseif ($log) {
                // Update the existing log
                $log->end_time = $currentTime;
                $currentSessionDuration = $log->end_time - $log->start_time;
                $log->duration = $currentSessionDuration;

                if ($request->event === 'left' || $request->event === 'completed') {
                    $log->status = $request->event;
                }

                $log->save();
                $duration = $currentSessionDuration; // Get duration from the current log update
            }

            // --- Video Progress Integration ---
            // Only update progress if there's a duration to add
            if ($duration > 0) {
                $progress = VideoProgress::firstOrCreate(
                    [
                        'student_id' => $studentId,
                        'video_id' => $request->video_id,
                    ],
                    [
                        'total_watch_time' => 0,
                        'is_completed' => false,
                    ]
                );

                // Add the new duration to the total watch time
                $progress->total_watch_time += $duration;

                // Mark video as completed if total watch time exceeds video duration
                $video = Video::find($request->video_id);
                if ($video && $progress->total_watch_time >= $video->duration) {
                    $progress->is_completed = true;
                }

                $progress->save();
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Video log error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }
}
