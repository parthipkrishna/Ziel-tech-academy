<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeedback extends Model
{
    use HasFactory;

    protected $table = 'student_feedbacks';

    protected $fillable = [
        'student_id',
        'module_id',
        'batch',
        'admission_number',
        'location',
        'contact_number',
        'status',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function histories()
    {
        return $this->hasMany(StudentFeedbackHistory::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'module_id');
    }
    
    public function session()
    {
        return $this->hasOne(StudentFeedbackSession::class, 'student_feedback_id');
    } 

    public static function getFeedbackStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'initiated' => 'Initiated',
            'scheduled' => 'Scheduled',
            'draft' => 'Draft',
            'completed' => 'Completed',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];
    }

}
