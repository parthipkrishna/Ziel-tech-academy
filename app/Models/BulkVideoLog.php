<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkVideoLog extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel's naming conventions)
    protected $table = 'bulk_video_logs';

    // Define which attributes are mass assignable
    protected $fillable = [
        'video_id',
        'error_message',
        'status',
    ];

    // Define relationships
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
