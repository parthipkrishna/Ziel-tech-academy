<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'video_id',
        'total_watch_time',
        'is_completed',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}