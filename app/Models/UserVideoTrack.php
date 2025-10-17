<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideoTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'video_status',
        'last_watched_at',
        'seek_position',
        'paused_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // Custom method to update video status and progress
    public function updateStatus($status, $seekPosition = null, $lastWatchedAt = null)
    {
        $this->update([
            'video_status' => $status,
            'seek_position' => $seekPosition,
            'last_watched_at' => $lastWatchedAt,
            'paused_at' => $status === 'paused' ? now() : null,
        ]);
    }
}
