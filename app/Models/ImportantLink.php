<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportantLink extends Model
{
    use HasFactory;

    protected $fillable = ['short_description', 'link', 'name', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

}
