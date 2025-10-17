<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopAchiever extends Model
{
    use HasFactory;

    protected $fillable = ['image','course_id', 'student_id', 'name', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}
