<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'course_id',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Get the student (user) associated with the subscription.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course associated with the subscription.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
