<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'attempt_count',
        'status',
        'unique_id',
    ];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];
    

    // Relations

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'attempt_id');
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class, 'exam_attempt_id', 'unique_id');
    }

}
