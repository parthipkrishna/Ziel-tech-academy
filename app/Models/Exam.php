<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if following Laravel's naming conventions)
    protected $table = 'exams';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'subject_id',
        'batch_id',
        'subject_session_id',
        'type',
        'name',
        'short_description',
        'description',
        'status',
        'duration',
        'total_marks',
        'minimum_passing_marks'
    ];

    /**
     * Get the subject associated with the exam.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the questions associated with the exam.
     */
    public function questions()
    {
        return $this->hasMany(ExamQuestion::class, 'exam_id');
    }
    /**
     * Get the batch associated with the exam.
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function subjectSession()
    {
        return $this->belongsTo(SubjectSession::class, 'subject_session_id');
    }
}
