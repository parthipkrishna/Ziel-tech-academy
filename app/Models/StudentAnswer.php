<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if following Laravel's naming conventions)
    protected $table = 'student_answers';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'exam_id',
        'question_id',
        'student_id',
        'selected_answer_id',
        'exam_attempt_id'
    ];

    /**
     * Get the exam associated with the student's answer.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Get the exam question associated with the student's answer.
     */
    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }

    /**
     * Get the user who gave the answer.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the selected answer chosen by the student.
     */
    public function selectedAnswer()
    {
        return $this->belongsTo(QuestionAnswer::class, 'selected_answer_id');
    }
    
    public function isCorrect(): bool
    {
        return $this->selectedAnswer?->is_correct ?? false;
    }
}
