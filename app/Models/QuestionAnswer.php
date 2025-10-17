<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if following Laravel's naming conventions)
    protected $table = 'question_answers';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct'
    ];

    /**
     * Get the exam question that owns the answer.
     */
    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }
}
