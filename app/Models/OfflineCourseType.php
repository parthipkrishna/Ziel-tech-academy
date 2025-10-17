<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflineCourseType extends Model
{
    use HasFactory;

    protected $table = 'offline_course_types';

    protected $fillable = [
        'offline_course_id',
        'base_name',
        'short_description',
        'full_description',
        'cover_image',
        'duration',
        'status',
    ];
    
    public function courses()
    {
        return $this->belongsTo(OfflineCourse::class);
    }

}
