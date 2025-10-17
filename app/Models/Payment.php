<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'course_id',
        'toolkit_id',
        'amount',
        'currency',
        'payment_gateway',
        'status',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // 🔗 Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function toolkit()
    {
        return $this->belongsTo(ToolKit::class);
    }
}
