<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentProgress;

class SubjectSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject_id',
        'description',
    ];

    protected $hidden = [
        'updated_at',
    ];

    // Append custom attribute in API response
    protected $appends = [
        'is_locked',
    ];

    // Store student ID dynamically
    protected $studentId;

    // 🔹 Set the student ID dynamically
    public function setStudentId(int $studentId): self
    {
        $this->studentId = $studentId;
        return $this;
    }

    /**
     * Subject relation
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

      public function recordedVideos()
    {
        return $this->hasMany(RecordedVideo::class, 'subject_session_id');
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'recorded_videos')
                    ->withPivot('is_enabled', 'video_order')
                    ->withTimestamps();
    }
    /**
     * Student progress for this session
     */
    public function progressForStudent(int $studentId)
    {
        return $this->hasOne(StudentProgress::class, 'module_id')
                    ->where('student_id', $studentId);
    }

    /**
     * Accessor for is_locked
     * Returns true if session is locked, false otherwise
     */
    public function getIsLockedAttribute(): bool
    {
        if (!$this->studentId) {
            return true; // default locked
        }

        $progress = $this->progressForStudent($this->studentId)->first();
        return $progress?->status !== 'unlocked'; // locked if status is not unlocked

    }
    public function assessments()
    {
        return $this->hasMany(Exam::class, 'subject_session_id');
    }

}
