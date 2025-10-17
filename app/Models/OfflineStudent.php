<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflineStudent extends Model
{
    use HasFactory;

    protected $table = 'offline_students';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'profile_photo',
        'guardian_name',
        'guardian_contact',
        'status',
        'user_id'
    ];

}
