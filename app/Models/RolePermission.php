<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';
    protected $fillable = [
        'role_id',
        'permission_ids',
    ];

    protected $casts = [
        'permission_ids' => 'array', 
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function permissionIds()
    {
        return json_decode($this->permission_ids, true);
    }
}
