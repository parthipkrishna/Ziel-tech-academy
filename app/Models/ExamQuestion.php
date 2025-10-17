<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if following Laravel's naming conventions)
    protected $table = 'exam_questions';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'exam_id',
        'question',
        'mark',
        'image',
    ];

    /**
     * Get the exam that owns the exam question.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id');
    }
}
