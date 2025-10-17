<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'date', 'time'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Accessors for pretty date/time formats
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('D d M Y'); 
        // Example: Thu 11 Sep 2025
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time ? Carbon::parse($this->time)->format('h:i A') : null;
        // Example: 10:00 AM
    }
}
