<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\QC;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\InfluencerCommission;
use App\Models\ReferralPayment;

function getAuthUserRoleName(): ?string
{
    $authUser = Auth::user();
    return DB::table('user_roles')
        ->join('roles', 'roles.id', '=', 'user_roles.role_id')
        ->where('user_roles.user_id', $authUser->id)
        ->value('roles.role_name');
}

function getAccessibleRoles(string $roleName): array
{
    $roleName = strtolower($roleName);
    $hierarchy = [
        'super admin' => ['admin', 'tutor', 'qc', 'manager'],
        'admin'       => ['tutor', 'qc'],
        'tutor'       => [],
        'qc'          => [],
    ];
    $accessible = $hierarchy[$roleName] ?? [];
    return array_filter($accessible, fn($r) => strtolower($r) !== $roleName);
}

function getLmsUsers()
{
    $authRoleName = getAuthUserRoleName();
    if (!$authRoleName) {
        return collect();
    }
    $accessibleRoles = getAccessibleRoles($authRoleName);
    if (empty($accessibleRoles)) {
        return collect();
    }
    return User::select('users.*', 'roles.role_name', 'user_roles.role_id')
    ->leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
    ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
    ->where('users.type', 'lms')
    ->whereIn(DB::raw('LOWER(roles.role_name)'), array_map('strtolower', $accessibleRoles))
    ->get();

}

function getLmsRoles()
{
    $user = auth()->user();
    if (!$user) {
        return collect();
    }

    $userRole = $user->roles()->orderBy('roles.id')->first();
    if (!$userRole) {
        abort(403, 'You do not have a role assigned.');
    }
    return Role::where('type', 'lms')
        ->where('status', 1)
        ->where('id', '>', $userRole->id)
        ->get();
}

function getLmsGenders () {
    return QC::where('type','lms')->get();
}

function checkQcOrTutorById($id){
    $role = Role::find($id);
    $flag = 0;
    $name = "";
    if($role->role_name == 'QC' || $role->role_name == 'Tutor'){
      $flag = 1;
      $name = $role->role_name;
    }
   return compact('flag', 'name'); 
}

function getLmsCourses() {
    return Course::where('type', 'lms')->get();
}

function getLmsSubjects() {
    return Subject::where('type', 'lms')->get();
}

function genderOptions()
{
    return [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
    ];
}
