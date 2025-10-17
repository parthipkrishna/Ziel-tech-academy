<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTestimonial extends Model
{
    use HasFactory;

    protected $table = 'student_testimonials'; // Table name

    protected $fillable = [
        'student_id',
        'content',
        'rating',
    ];

    /**
     * Define relationship with the User (Student).
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
