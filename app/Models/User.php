<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;  // Include HasApiTokens here

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',  
        'email',
        'phone',        
        'password',
        'profile_image',
        'status',
        'type',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'roles',
        'status',
        'type',
        'remember_token'
    ];
    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }  
    public function hasPermission($slug, $action = null)
    {
        // Define roles that have full access
        $fullAccessRoles = ['Super Admin', 'Admin'];
        // If the user has any of the full-access roles, allow everything
        if ($this->roles()->whereIn('role_name', $fullAccessRoles)->exists()) {
            return true;
        }
        // Get user's first role (if user has multiple roles, adjust accordingly)
        $role = $this->roles()->first();
        if (!$role) {
            return false;
        }
        // Get permissions for this role
        $rolePermission = \App\Models\RolePermission::where('role_id', $role->id)->first();
        if (!$rolePermission || !$rolePermission->permission_ids) {
            return false;
        }
        $permissionIds = json_decode($rolePermission->permission_ids, true) ?? [];
        // Build full permission name like "web-banner.create"
        $permissionName = $action ? "$slug.$action" : $slug;
        return \App\Models\Permission::whereIn('id', $permissionIds)
            ->where('permission_name', $permissionName)
            ->exists();
    }

    public function role()
    {
        return $this->hasOne(UserRole::class);
    }

    public function qc()
    {
        return $this->hasOne(QC::class);
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    // Accessor to get student id easily
    public function getStudentIdAttribute(): ?int
    {
        return $this->studentProfile?->id;
    }
    
    public function userRoles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->userRoles()->where('role_name', $roleName)->exists();
    }

    public function tutorBatches()
    {
        return $this->hasOne(Tutor::class, 'user_id')->with('tutorBatchesRelation');
    }

    // In User.php model
    public function getBatchesByCourse($courseId)
    {
        $studentId = optional($this->studentProfile)->id;

        if (!$studentId) {
            return collect(); // return empty collection if no student profile
        }

        return Batch::where('course_id', $courseId)
            ->whereHas('studentBranches', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->get();
    }
}
