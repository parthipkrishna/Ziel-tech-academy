<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\StudentProgress;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Exception;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $studentId = $student->id;

            $courseIds = Subscription::where('student_id', $student->id)
                ->where('status', 'active')
                ->pluck('course_id');

            $subjectIds = Subject::whereIn('course_id', $courseIds)->pluck('id');

            $excludedExamIds = ExamAttempt::where('student_id', $student->id)
                ->whereIn('status', ['Passed'])
                ->pluck('exam_id')
                ->toArray();

            $exams = Exam::where('type', 'Exam')
                ->whereIn('subject_id', $subjectIds)
                ->whereNotIn('id', $excludedExamIds) 
                ->with(['subject', 'subject.subjectSessions'])
                ->get()
                ->map(function ($exam) use ($studentId) {
                    $totalSessions = $exam->subject->subjectSessions->count();

                    $completedSessions = StudentProgress::where('student_id', $studentId)
                        ->where('subject_id', $exam->subject_id)
                        ->where('status', 'completed')                                                                                              
                        ->count();

                    $is_locked = $completedSessions < $totalSessions;

                    return [
                        'id' => $exam->id,
                        'title' => $exam->title,
                        'subject' => $exam->subject->name,
                        'duration' => $exam->duration,
                        'question_count' => $exam->questions->count(),
                        'is_locked' => $is_locked,
                    ];
                });

            return view('student.exam.list_exams', [
                'exams' => $exams,
                'studentId' => $student->id,
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
                ->where('type', 'Exam')
                ->with(['questions.answers'])
                ->firstOrFail();

            // Check if exam is already passed
            $isPassed = ExamAttempt::where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->where('status', 'Passed')
                ->exists();

            if ($isPassed) {
                return redirect()->route('student.portal.exam-mcq')->with('error', 'This assessment has already been completed.');
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

            return view('student.exam.index', [
                'questions' => $questions,
                'studentId' => $student->id,
                'exam' => $exam,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.portal.exam-mcq')->with('error', 'Failed to load assessment: ' . $e->getMessage());
        }
    }
}
