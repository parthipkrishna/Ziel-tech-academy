<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'subject_session_id',
        'video_id',
        'start_time',
        'end_time',
        'duration',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function session()
    {
        return $this->belongsTo(SubjectSession::class, 'subject_session_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
