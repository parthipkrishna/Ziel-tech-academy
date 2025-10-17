<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events'; // Table name

    protected $fillable = [
        'name',
        'date',
        'description',
        'location',
    ];
    public function media()
    {
        return $this->hasMany(EventMedia::class);
    }
}
