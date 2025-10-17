<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'profile_photo',
        'admission_number',
        'admission_date',
        'guardian_name',
        'guardian_contact',
        'status',
        'user_id',
        'is_device_blocked'
    ];

    protected $hidden = [
        'remember_token',
        'created_at',
        'updated_at',
        'zip_code',
        'guardian_contact',
        'guardian_name',
    ];

    public function testimonials()
    {
        return $this->hasMany(StudentTestimonial::class, 'student_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(StudentFeedback::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'student_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'student_id')
                    ->where('status', 'active');
    }
    
    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_student', 'student_id', 'batch_id')->withTimestamps();
    }

    public function studentBatches()
    {
        return $this->belongsToMany(Batch::class, 'batch_student', 'student_id', 'batch_id')
                    ->withTimestamps();
    }
     /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

        /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullAddressAttribute()
    {
        return [
            'name'   => $this->full_name,
            'phone'  => $this->phone,
            'other'  => $this->address, // free text or JSON column
            'state'  => $this->state,
            'pin'    => $this->zip_code,
        ];
    }

}