<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusFacility extends Model
{
    protected $table = 'campus_facilities';

    protected $fillable = [
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
