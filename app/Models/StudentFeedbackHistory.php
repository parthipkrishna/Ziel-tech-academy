<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeedbackHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_feedback_id',
        'module_id',
        'qc_user_id',
        'student_summary',
        'qc_feedback_summary',
        'video_rating',
        'practical_rating',
        'understanding_rating',
        'status',
    ];

    // Relationships
    public function studentFeedback()
    {
        return $this->belongsTo(StudentFeedback::class);
    }

    public function module()
    {
        return $this->belongsTo(Subject::class);
    }

    public function qc()
    {
        return $this->belongsTo(QC::class);
    }

    public function feedback()
    {
        return $this->belongsTo(StudentFeedback::class, 'student_feedback_id');
    }

    public static function getStatusOptions()
    {
        return [
            'draft' => 'Draft',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'module_id');
    }

}
