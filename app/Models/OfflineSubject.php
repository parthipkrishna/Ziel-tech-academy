<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflineSubject extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'offline_subjects';

    // Fillable columns
    protected $fillable = [
        'name',
        'short_desc',
        'desc',
        'status',
        'course_id',
    ];
    
    protected $hidden = [
         'created_at',
         'updated_at',
    ];
    public function course()
    {
        return $this->belongsTo(OfflineCourse::class);
    }
}
