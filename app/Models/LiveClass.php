<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LiveClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'meeting_link', 
        'start_time', 
        'end_time', 
        'status', 
        'thumbnail_image', 
        'short_summary', 
        'summary',
        'subject_id',
        'tutor_id',
        'batch_id',
        'subject_session_id'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    protected $appends = [
        'date_text',
        'start_time_text',
        'end_time_text',
    ];

    // ✅ Accessors
    public function getDateTextAttribute(): ?string
    {
        return $this->start_time 
            ? Carbon::parse($this->start_time)->format('F j, Y') // March 1, 2025
            : null;
    }

    public function getStartTimeTextAttribute(): ?string
    {
        return $this->start_time 
            ? Carbon::parse($this->start_time)->format('g A') // 9 PM
            : null;
    }

    public function getEndTimeTextAttribute(): ?string
    {
        return $this->end_time 
            ? Carbon::parse($this->end_time)->format('g A') // 1 PM
            : null;
    }

    // 🔗 Relationships
    public function participants()
    {
        return $this->hasMany(LiveClassParticipant::class);
    }

    public function sessionLogs()
    {
        return $this->hasMany(ClassSessionLog::class);
    }
    
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
    
    /**
     * Accessor: Get total student count.
     */
    public function getTotalParticipantsAttribute()
    {
        return $this->participants()->count();
    }
}
