<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video',
        'description',
        'is_enabled',
        'is_bulk_uploaded',
        'order',
        'thumbnail',
        'status',
        'duration'
    ];
    
    protected $hidden = [
         'created_at',
         'updated_at',
         'order'
    ];

    /**
     * Get the subject associated with the video.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'recorded_videos')
                    ->withPivot('subject_session_id', 'is_enabled', 'video_order')
                    ->withTimestamps();
    }

}
