<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $table = 'student_enrollments'; // Table name

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
    ];

    /**
     * Define relationship with the User (Student).
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Define relationship with the Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public static function getStatusOptions()
    {
        return ['active', 'cancelled', 'enrolled', 'completed','free']; 
    }
}
