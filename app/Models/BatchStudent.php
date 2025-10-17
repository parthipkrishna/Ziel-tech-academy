<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchStudent extends Model
{
    protected $table = 'batch_student'; // Explicit table name since it's a pivot

    protected $fillable = [
        'batch_id',
        'student_id',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
