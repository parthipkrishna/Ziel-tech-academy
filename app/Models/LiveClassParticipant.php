<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiveClassParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_class_id',
        'student_id',
        'batch_id',
        'join_time',
        'leave_time',
    ];

    public function liveClass()
    {
        return $this->belongsTo(LiveClass::class, 'live_class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
