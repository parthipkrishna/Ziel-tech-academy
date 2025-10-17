<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflineCourse extends Model
{
    use HasFactory;

    protected $table = 'offline_courses';
    
    protected $fillable = [
        'name',
        'total_fee',
        'advance_fee',
        'monthly_fee',
        'monthly_fee_duration',
    ];
    
    public function offlineSubjects()
    {
        return $this->hasMany(OfflineSubject::class, 'course_id');
    }

    public function offlineCourseTypes()
    {
        return $this->hasMany(OfflineCourseType::class, 'offline_course_id');
    }

}
