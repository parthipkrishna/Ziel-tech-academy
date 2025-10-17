<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSessionLog extends Model
{
     protected $fillable = [
        'live_class_id', 
        'user_id', 
        'action', 
    ];

    public function liveClass() {
        return $this->belongsTo(LiveClass::class);
    }
}
