<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordedVideo extends Model
{
    use HasFactory;

    // Define the fillable attributes based on the migration
    protected $fillable = [
        'subject_id', 
        'subject_session_id',
        'video_id',
        'is_enabled',
        'video_order',
    ];
    
    protected $hidden = [
         'created_at',
         'updated_at',
         'video_session_id',
         'video_id'
    ];

    /**
     * Get the subject associated with this recorded video.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }


    /**
     * Get the video session associated with this recorded video.
     */
    public function subjectSession()
    {
        return $this->belongsTo(SubjectSession::class);
    }

    /**
     * Get the video associated with this recorded video.
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
