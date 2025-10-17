<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'auth_id';

    protected $fillable = [
        'user_id', 'auth_type', 'auth_value'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
