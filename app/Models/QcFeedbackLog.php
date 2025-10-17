<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcFeedbackLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'qc_user_id',
        'student_feedback_id',
        'action',
        'action_at',
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];

    // Relationships
    public function qcUser()
    {
        return $this->belongsTo(QC::class);
    }

    public function studentFeedback()
    {
        return $this->belongsTo(StudentFeedback::class);
    }
}
