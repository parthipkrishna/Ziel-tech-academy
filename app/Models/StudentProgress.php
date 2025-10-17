<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $table = 'student_progress';

    protected $fillable = [
        'student_id',
        'subject_id',
        'module_id',
        'status',
        'unlocked_at',
        'completed_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // 🔗 The student who owns this progress
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // 🔗 The subject related to this progress
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // 🔗 The module related to this progress
    public function module()
    {
        return $this->belongsTo(SubjectSession::class, 'module_id');
    }
}
