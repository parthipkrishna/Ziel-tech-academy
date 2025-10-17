<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'subjects';

    // Fillable columns
    protected $fillable = [
        'name',
        'short_desc',
        'desc',
        'status',
        'course_id',
        'sections',
        'total_hours',
        'mobile_thumbnail',
        'web_thumbnail',
        'type',
    ];
    
    protected $hidden = [
         'created_at',
         'updated_at',
    ];
    
    /**
     * Get the course that owns the subject.
     * Many-to-One Relationship: Subject belongs to Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the recorded videos associated with this subject.
     * One-to-many relationship (Subject has many RecordedVideos).
     */
    public function recordedVideos()
    {
        return $this->hasMany(RecordedVideo::class);
    }

    public function recordedVideosCount()
    {
        return $this->recordedVideos()->count();
    }

    /**
     * Get the live classes associated with this subject.
     * One-to-many relationship (Subject has many LiveClasses).
     */
    public function liveClasses()
    {
        return $this->hasMany(LiveClass::class);
    }

   public function videos()
   {
        return $this->belongsToMany(Video::class, 'recorded_videos')
                    ->withPivot('subject_session_id', 'is_enabled', 'video_order')
                    ->withTimestamps();
   }
   public function subjectSessions()
   {
        return $this->hasMany(SubjectSession::class);
   }

    public function sessions()
    {
        return $this->hasMany(SubjectSession::class);
    }
}