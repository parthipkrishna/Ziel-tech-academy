<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'section_name',
        'permission_name',
        'type',
        'status'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
