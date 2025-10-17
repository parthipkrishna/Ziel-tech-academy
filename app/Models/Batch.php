<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'student_limit',
        'batch_number',
        'tutor_id',
        'batch_in_charge_id',
        'qc_ids',
        'course_id',
        'status',
    ];

    protected $casts = [
        'qc_ids' => 'array',
        'status' => 'boolean',
    ];

    // Relationships

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
    public function batchTutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id');
    }


    public function getQcsAttribute()
    {
        return User::whereIn('id', $this->qc_ids ?? [])->get();
    }

    public function batchInCharge()
    {
        return $this->belongsTo(User::class, 'batch_in_charge_id');
    }

    public function channels()
    {
        return $this->hasMany(BatchChannel::class, 'batch_id');
    }

    public function getQcIdsAttribute($value)
    {
        $decoded = json_decode($value, true);
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return $decoded;
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function exams()
    {
        return $this->hasMany(Exam::class, 'batch_id');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'batch_student', 'batch_id', 'student_id');
    }
    
    public function liveClasses()
    {
        return $this->hasMany(LiveClass::class, 'batch_id');
    }
}
