<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentScore;

class ExamHistoryController extends Controller
{

    public function historyIndex()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $scores = StudentScore::with(['exam', 'attempt'])
            ->where('student_id', $student->id)
            ->get()
            ->sortByDesc(function ($score) {
                return $score->attempt?->created_at;
            });

        return view('student.exam_history.index', compact('scores'));
    }

    public function historyView($examAttemptId)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $score = StudentScore::with([
            'exam.questions.answers',
            'answers.selectedAnswer',
            'attempt'
        ])
            ->where('student_id', $student->id)
            ->where('exam_attempt_id', $examAttemptId)
            ->firstOrFail();

        return view('student.exam_history.history', compact('score'));
    }
}