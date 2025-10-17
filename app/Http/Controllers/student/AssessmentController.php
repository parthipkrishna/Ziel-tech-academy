<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\StudentProgress;
use App\Models\Subscription;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use Exception;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;



class AssessmentController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $studentId = $student->id;

            $courseIds = Subscription::where('student_id', $studentId)
                ->where('status', 'active')
                ->pluck('course_id');

            $subjects = Subject::whereIn('course_id', $courseIds)
                ->with(['subjectSessions.videos' => function($query) {
                    $query->orderBy('created_at');
                }, 'subjectSessions.assessments', 'subjectSessions.assessments.questions'])
                ->get();

            $exams = collect();

            $isContentUnlocked = true; 

            foreach ($subjects as $subject) {
                $sessions = $subject->subjectSessions->sortBy('created_at')->values();
                 $firstSessionId = $sessions->first()?->id;

                foreach ($sessions as $session) {
                    $videos = $session->videos->sortBy('created_at')->values();
                    $forceUnlock = $session->id === $firstSessionId;

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
                        $hasPassed = ExamAttempt::where('student_id', $studentId)
                            ->where('exam_id', $assessment->id)
                            ->where('status', 'Passed')
                            ->exists();

                        if ($hasPassed) {
                            continue;
                        }

                       $assessment->is_locked = $forceUnlock ? false : !$isContentUnlocked;

                        $exams->push([
                            'id' => $assessment->id,
                            'title' => $assessment->title,
                            'subject' => $subject->name,
                            'duration' => $assessment->duration,
                            'question_count' => $assessment->questions->count(),
                            'is_locked' => $assessment->is_locked,
                        ]);
                    }
                }
            }

            return view('student.assessment.list_assessments', [
                'exams' => $exams,
                'studentId' => $studentId,
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load assessments: ' . $e->getMessage());
        }
    }

    public function show($examId)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();

            // Verify the exam exists and is accessible
            $exam = Exam::where('id', $examId)
                ->where('type', 'Assessment')
                ->with(['questions.answers'])
                ->firstOrFail();

            // Check if exam is already passed
            $isPassed = ExamAttempt::where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->where('status', 'Passed')
                ->exists();

            if ($isPassed) {
                return redirect()->route('student.exam.assessment')->with('error', 'This assessment has already been completed.');
            }

            // Prepare questions for the selected exam
            $questions = $exam->questions->shuffle()->map(function ($q) use ($exam) {
                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'image' => $q->image ? asset('storage/' . $q->image) : null,
                    'options' => $q->answers->shuffle()->map(function ($a) {
                        return [
                            'id' => $a->id,
                            'text' => $a->answer_text,
                        ];
                    })->values()->all(),
                    'answer' => optional($q->answers->firstWhere('is_correct', 1))->id,
                    'exam_id' => $exam->id,
                    'mark' => $q->mark,
                ];
            })->values();

            return view('student.assessment.index', [
                'questions' => $questions,
                'studentId' => $student->id,
                'exam' => $exam,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.exam.assessment')->with('error', 'Failed to load assessment: ' . $e->getMessage());
        }
    }
}
