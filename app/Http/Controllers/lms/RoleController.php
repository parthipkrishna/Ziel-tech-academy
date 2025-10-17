<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; 

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('type', 'lms')->get();
        $permissions = Permission::where('type', 'lms')->where('status', 1)->get();

        return view('lms.sections.role.roles')->with([
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'role_name' => 'required|string|max:255',
                'status' => 'nullable|boolean',
                'permission_ids' => 'nullable|array'
            ]);

            // Create Role
            $role = Role::create([
                'role_name' => $request->role_name,
                'type' => 'lms',
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // Save permissions if provided
            if ($request->has('permission_ids')) {
                RolePermission::create([
                    'role_id' => $role->id,
                    'permission_ids' => json_encode($request->permission_ids),
                ]);
            }

            return redirect()->back()->with(['status' => true, 'message' => 'Role created successfully.']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $role = Role::findOrFail($id);
        $role_permissions = RolePermission::all();
        $permissions = Permission::where('type', 'lms')
        ->where('status', 1)
        ->orderBy('section_name')
        ->orderBy('permission_name')
        ->get()
        ->groupBy('section_name');
        $rolePermissionsMap = [];
        foreach ($role_permissions as $item) {

            $rolePermissionsMap[$item->role_id] = json_decode($item->permission_ids, true);
        }

        return view('lms.sections.role.inc.edit-role-modal')->with([
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
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
            'status' => 'nullable|boolean',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->update([
            'role_name' => $request->role_name,
            'status' => $request->has('status') ? 1 : 0,
        ]);

            $permissionIds = $request->input('permission_ids', []);

            $rolePermission = RolePermission::where('role_id', $id)->first();

            if ($rolePermission) {
                // Update existing
                $rolePermission->update([
                    'permission_ids' => json_encode($permissionIds),
                ]);
            } else {
                // Create new if not exists
                RolePermission::create([
                    'role_id' => $id,
                    'permission_ids' => json_encode($permissionIds),
                ]);
            }

            return redirect()->route('lms.roles')->with([
                'status' => true,
                'message' => 'Role updated successfully.',
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('lms.roles')->with([
                'status' => false,
                'message' => 'Role update failed.',
                'error' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role= Role::findOrFail($id); // Find the user by ID
        $role->delete(); // Delete the user
    
        return redirect()->route('lms.roles')->with('message', 'User deleted successfully!');
    }

    public function ajaxList(Request $request)
    {
        $roles = Role::query()->latest();

        return DataTables::of($roles)
            ->addColumn('action', function ($role) {
                if ($role->system_reserved) {
                    return '<span class="bg-white px-2 py-1 rounded" title="System Reserved - Cannot Edit/Delete">
                                <i class="mdi mdi-lock-outline" style="color: rgba(121, 115, 115, 0.6); font-size: 24px;"></i>
                            </span>';
                }

                $actions = '';
                if (auth()->user()->hasPermission('roles.update')) {
                    $actions .= '<a href="' . route('lms.roles.edit', $role->id) . '" class="action-icon">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                </a>';
                }

                if (auth()->user()->hasPermission('roles.delete')) {
                    // Modal button
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#deleteRoleModal' . $role->id . '">
                                    <i class="mdi mdi-delete"></i>
                                </a>';

                    // Modal itself
                    $actions .= '<div id="deleteRoleModal' . $role->id . '" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-body p-4 text-center">
                                                <i class="ri-information-line h1 text-info"></i>
                                                <h4 class="mt-2">Heads up!</h4>
                                                <p class="mt-3">Do you want to delete this role?</p>
                                                <button type="button" class="btn btn-danger my-2 confirm-delete-role" data-id="' . $role->id . '">Delete</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                }

                return $actions;
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

}
