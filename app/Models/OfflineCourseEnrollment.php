<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflineCourseEnrollment extends Model
{
    use HasFactory;

    protected $table = 'offline_course_enrollments';

    protected $fillable = [
        'offline_course_id',
        'offline_course_type_id',
        'student_id',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(OfflineCourse::class, 'offline_course_id');
    }

    public function courseType()
    {
        return $this->belongsTo(OfflineCourseType::class, 'offline_course_type_id');
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public static function getStatusOptions()
    {
        return ['active', 'cancelled', 'enrolled', 'completed']; 
    }

}
