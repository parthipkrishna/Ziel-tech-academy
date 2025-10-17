<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamParticipant extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if following Laravel's naming conventions)
    protected $table = 'exam_participants';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'exam_id',
        'student_id',
        'exam_attempt_id',
        'status',
        'joined_at',
        'left_at',
        'completed_at'
    ];

    /**
     * Get the exam that the participant is taking.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Get the user that is participating in the exam.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
