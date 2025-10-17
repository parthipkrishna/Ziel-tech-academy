<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'email',
        'phone',
        'is_email_verified',
        'is_phone_verified',
    ];

    /**
     * Get the user that owns the verification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
