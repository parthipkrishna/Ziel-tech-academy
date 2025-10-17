<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'delivered_count',
        'image',
        'link',
        'type',
        'category_type',
        'status',
        'extra_info',
        'student_ids',
        'batch_ids'
    ];

    protected $casts = [
        'student_ids' => 'array',
        'batch_ids'   => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'status',
        'user_id',
        'student_ids',
        'batch_ids',
        'delivered_count'
    ];

    protected $appends = ['date', 'time'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Notification types
    public static function getTypes()
    {
        return [
            'local' => 'Local',
            'push'  => 'Push',
        ];
    }

    public static function getCategoryTypes()
    {
        return [
            'student' => 'Student',
            'batch'   => 'Batch',
            'general' => 'General',
        ];
    }

    /**
     * Custom Accessor for date
     */
    public function getDateAttribute()
    {
        $created = Carbon::parse($this->attributes['created_at']);

        if ($created->isToday()) {
            return 'Today';
        } elseif ($created->isYesterday()) {
            return 'Yesterday';
        }

        return $created->format('M d Y'); // e.g. Jan 15 2025
    }

    /**
     * Custom Accessor for time
     */
    public function getTimeAttribute()
    {
        $created = Carbon::parse($this->attributes['created_at']);
        return $created->format('h:i A'); // e.g. 10:45 AM
    }
}
