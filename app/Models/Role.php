<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_name',
        'system_reserved',
        'status',
        'type'
    ];
    
     protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the permissions associated with the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Get the users associated with the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function permissionsRelation()
    {
        return $this->hasOne(RolePermission::class, 'role_id');
    }

    public function getPermissionsAttribute()
    {
        return $this->permissionsRelation;
    }

}
