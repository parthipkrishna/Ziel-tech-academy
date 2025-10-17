<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    protected $fillable = [
        'name',
        'short_description',
        'full_description',
        'status',
        'target_audience',
        'languages',
        'course_fee',
        'toolkit_fee',
        'gst_amount',
        'cover_image_web',
        'cover_image_mobile',
        'total_hours',
        'tags',
        'type',
        'course_end_date',
        'min_loyalty_points',
        'loyalty_points_earn'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'languages' => 'array', // Casting 'languages' to an array
        'course_fee' => 'decimal:2',
        'toolkit_fee' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_hours' => 'integer',
        'tags' => 'array',
    ];

    protected $appends = ['is_subscribed'];
    /**
     * Get the subjects (modules) associated with the course.
     * One-to-Many Relationship: A Course has many Subjects.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the tutors associated with the course.
     */
    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'course_tutor');
    }

    /**
     * Calculate GST amount (18% of the course fee).
     */
    public function calculateGstAmount()
    {
        return $this->course_fee * 0.18;
    }

    /**
     * Get the GST amount for the course.
     */
    public function getGstAmountAttribute()
    {
        return $this->calculateGstAmount();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getIsSubscribedAttribute(): bool
    {
        $studentId = auth()->user()->studentProfile->id ?? null;

        if (!$studentId) {
            return false;
        }

        return $this->subscriptions()
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->exists();
    }

    public function toolkits()
    {
        return $this->hasMany(ToolKit::class);
    }
}
