<?php

namespace App\Http\Controllers\dashboard\admin;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Models\Role;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('type', 'web')->get(); 
        return view('dashboard.roles.roles',compact('roles'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::where('type','web')->where('status', 1)->get();
        return view('dashboard.roles.add')->with(compact('permissions'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'permissions' => 'array',
            'status'=> 'required'
        ]);
        try {
            $role = new Role();
            $role->role_name = $request->input('role_name');
            $role->type = 'web';
            $role->status = $request->input('status');
            $role->system_reserved = false;
            $success = $role->save();
            if ($success) {
                if ($request->has('permissions')) {
                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $role->id;
                    $rolePermission->permission_ids = json_encode($request->input('permissions'));
                    $rolePermission->save();
                }
                return redirect()->route('admin.roles.index')->with('message', 'Role added successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Something went wrong. Please try again.'])
                ->withInput();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {   
        $role = Role::findOrFail($id);
        $role_permissions = RolePermission::all();
        $permissions = Permission::where('type', 'web')
        ->where('status', 1)
        ->orderBy('section_name')
        ->orderBy('permission_name')
        ->get()
        ->groupBy('section_name');
        $rolePermissionsMap = [];
        foreach ($role_permissions as $item) {

            $rolePermissionsMap[$item->role_id] = json_decode($item->permission_ids, true);
        }

        return view('dashboard.roles.edit')->with([
            'role' => $role,
            'role_permissions' => $rolePermissionsMap,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $request->validate([
        'role_name' => 'required|string|max:255',
        'permission_ids' => 'array',
        'status' => 'required|in:0,1'
    ]);
        $role = Role::findOrFail($id);
        $role->update([
            'role_name' => $request->input('role_name') ?: $role->role_name,
            'status' => $request->input('status'),
        ]);
        $permissionIds = $request->input('permission_ids', []);
        $rolePermission = RolePermission::where('role_id', $role->id)->first();
        if ($rolePermission) {
            $rolePermission->update([
                'permission_ids' => json_encode($permissionIds),
            ]);
        } else {
            RolePermission::create([
                'role_id' => $role->id,
                'permission_ids' => json_encode($permissionIds),
            ]);
        }
        return redirect()->route('admin.roles.index')->with(['message' => 'Role and permissions successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $success = Role::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}