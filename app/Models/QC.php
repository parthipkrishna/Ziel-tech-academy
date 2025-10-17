<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QC extends Model
{
    use HasFactory;

    protected $table = 'qcs';

    protected $fillable = [
        'user_id',
        'joined_date',
        'age',
        'gender',
        'qualifications',
        'batch_ids',  // Added batch_ids to fillable
    ];

    protected $casts = [
        'batch_ids' => 'array',  // Automatically cast to an array when accessed
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // If you want to query or manage batches related to QC:
    public function getBatchesAttribute()
    {
        return Batch::find($this->batch_ids);  // Fetch batch details from batch_ids
    }
}
