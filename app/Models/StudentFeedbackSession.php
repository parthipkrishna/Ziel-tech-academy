<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeedbackSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_feedback_id',
        'qc_user_id',
        'scheduled_at',
        'meeting_link',
        'status',
        'confirmed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    // Relationships
    public function studentFeedback()
    {
        return $this->belongsTo(StudentFeedback::class);
    }

    public function qc()
    {
        return $this->belongsTo(QC::class);
    }

    public function feedback()
    {
        return $this->belongsTo(StudentFeedback::class, 'student_feedback_id');
    }

}
