<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDeviceInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'device_id',
        'device_type',
        'device_name',
        'ip_address',
        'browser',
        'is_approved'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
