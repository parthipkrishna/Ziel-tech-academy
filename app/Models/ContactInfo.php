<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $table = 'contact_info'; // Table name

    protected $fillable = [
        'phone',
        'email',
        'address',
        'google_map_link',
    ];
}
