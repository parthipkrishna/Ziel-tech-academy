<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\QC;
use App\Models\Tutor;
use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Storage;
use Yajra\DataTables\Facades\DataTables; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = getLmsUsers();

        return view('lms.sections.user.users')->with(compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms.sections.user.add-user');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            Log::info('User store request received', $request->all());

            // Step 1: Define validation rules separately
            // Base rules apply to ALL users
            $baseRules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|digits_between:10,15|unique:users,phone',
                'password' => 'required|string|min:6',
                'type' => 'required|string',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'role' => 'required',
                'status' => 'boolean',
            ];
            $validatedBaseData = $request->validate($baseRules);

            // Now safe to call checkQcOrTutorById
            $roleInfo = checkQcOrTutorById($validatedBaseData['role']);
            $roleName = $roleInfo['name'] ?? null;
            // Role-specific rules only for QC and Tutor
            $roleSpecificRules = [];

            if ($roleName === 'QC' || $roleName === 'Tutor') {
                $roleSpecificRules = [
                    // For these roles, the fields are likely required, not nullable
                    'age' => 'required|integer|min:18',
                    'gender' => 'required|string|in:male,female,other',
                    'qualifications' => 'required|string|max:255',
                    'joined_date' => 'required|date',
                ];
            }

            // Merge rules and validate
            $validatedData = $request->validate(array_merge($baseRules, $roleSpecificRules));
            Log::info('Validation passed', $validatedData);

            DB::beginTransaction();

            // Step 2: Handle profile image upload (no change needed here)
            if ($request->hasFile('profile_image')) {
                $path = $request->file('profile_image')->store('lms/assets/images/users', 'public');
                $validatedData['profile_image'] = $path;
                Log::info('Profile image uploaded', ['path' => $path]);
            }

            // Step 3: Separate data for the User model
            // Define keys that do NOT belong to the users table
            $roleSpecificKeys = ['age', 'gender', 'qualifications', 'joined_date'];
            
            // Use Laravel's Arr::except helper to get only the user data
            $userData = Arr::except($validatedData, $roleSpecificKeys);

            // Create the user with user-specific data only
            $userData['password'] = \Hash::make($userData['password']);
            $user = \App\Models\User::create($userData);
            Log::info('User created', ['user_id' => $user->id]);

            // Step 4: Assign role (no change needed here)
            \App\Models\UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->role
            ]);
            Log::info('User role assigned', ['user_id' => $user->id, 'role_id' => $request->role]);

            // Step 5: Insert role-specific data if the role is QC or Tutor
            if ($roleName === 'QC' || $roleName === 'Tutor') {
                // Get only the role-specific data from the validated data
                $roleData = Arr::only($validatedData, $roleSpecificKeys);
                $roleData['user_id'] = $user->id; // Add the new user's ID

                if ($roleName === 'QC') {
                    \App\Models\QC::create($roleData);
                    Log::info('QC record created', $roleData);
                } elseif ($roleName === 'Tutor') {
                    \App\Models\Tutor::create($roleData);
                    Log::info('Tutor record created', $roleData);
                }
            }

            DB::commit();
            Log::info('Transaction committed successfully.');

            return response()->json(['message' => 'User created successfully']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Unexpected exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => collect($e->getTrace())->take(3)->toArray()
            ]);

            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('lms.sections.user.edit-user',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|integer|exists:roles,id',
            'phone' => 'nullable|digits_between:10,15',
            'password' => 'nullable|string|min:6',
            'type' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            Log::info('Updating user', ['user_id' => $user->id]);

            $validatedData['status'] = (int) $request->status;
            $validatedData['phone'] = $request->phone ?: null;

            if ($request->hasFile('profile_image')) {
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                    Log::info('Old profile image deleted', ['user_id' => $user->id]);
                }

                $validatedData['profile_image'] = $request->file('profile_image')->store('lms/assets/images/users', 'public');
                Log::info('New profile image uploaded', ['user_id' => $user->id, 'path' => $validatedData['profile_image']]);
            }

            if ($request->filled('password')) {
                $validatedData['password'] = Hash::make($request->password);
                Log::info('Password updated', ['user_id' => $user->id]);
            } else {
                unset($validatedData['password']);
            }

            $user->update($validatedData);
            Log::info('User data updated', ['user_id' => $user->id, 'data' => $validatedData]);

            // Update or create user role
            $user->role()->updateOrCreate(
                ['user_id' => $user->id],
                ['role_id' => $request->role]
            );
            Log::info('User role updated or created', ['user_id' => $user->id, 'role_id' => $request->role]);

            // Get the new role name
            $roleName = $user->role->role->role_name ?? null;
            Log::info('Role name resolved', ['user_id' => $user->id, 'role_name' => $roleName]);

            if (!$roleName || !in_array($roleName, ['QC', 'Tutor'])) {
                throw new Exception("Invalid or missing role.");
            }

            // Common fields
            $commonData = [
                'user_id' => $user->id,
                'joined_date' => $request->joined_date ?? now(),
                'age' => $request->age,
                'gender' => $request->gender,
                'qualifications' => $request->qualifications,
            ];

            if ($roleName === 'QC') {
                $user->qc()->updateOrCreate(['user_id' => $user->id], $commonData);
                $user->tutor()->delete();
                Log::info('QC profile updated or created', $commonData);
            } elseif ($roleName === 'Tutor') {
                $user->tutor()->updateOrCreate(['user_id' => $user->id], $commonData);
                $user->qc()->delete();
                Log::info('Tutor profile updated or created', $commonData);
            }

            DB::commit();
            Log::info('User update transaction committed', ['user_id' => $user->id]);

            return redirect()->back()->with(['status' => true, 'message' => 'User updated successfully']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('User update failed', ['user_id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with(['status' => false, 'message' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }


    public function enable(Request $request,$id){
        $status = $request->enabled;
        try {
            $user = User::findOrFail($id); 
            $user->update(['status' => $status]);
            return response(['status' => true,'message' => 'User status changed success']);
        } catch (\Throwable $th) {
            return response(['status' => false,'message' => 'User status changed failed','error' => $th->getMessage()]);
        }
        
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }
    public function ajaxUserList(Request $request)
    {
        $users = User::with('roles')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('role_name', 'Student');
            })
            ->select('users.*');

        return DataTables::of($users)
            ->addColumn('profile', function ($user) {
                if ($user->profile_image) {
                    return '<img src="' . env('STORAGE_URL') . '/' . $user->profile_image . '" class="me-2"  width="60 height="40" 
                                style="object-fit: cover;">';
                } else {
                    return '<span class="small text-danger">No Image</span>';
                }
            })
            ->addColumn('role', function ($user) {
                return $user->roles->pluck('role_name')->join(', ');
            })
            ->filterColumn('role', function ($query, $keyword) {
                $query->whereHas('roles', function ($q) use ($keyword) {
                    $q->where('role_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('status', function ($user) {
                $checked = $user->status ? 'checked' : '';
                return '
                    <div class="mb-3 mt-3">
                        <input type="checkbox" class="status-toggle" id="switch-status' . $user->id . '" value="1" ' . $checked . ' data-id="' . $user->id . '" data-switch="success" />
                        <label for="switch-status' . $user->id . '" data-on-label="Yes" data-off-label="No"></label>
                    </div>';
            })
            ->addColumn('action', function ($user) {
                $editModal = view('lms.sections.user.inc.edit-user-modal', compact('user'))->render();
                $deleteModal = view('lms.sections.user.inc.delete-alert-modal', compact('user'))->render();

                $editBtn = auth()->user()->hasPermission('users.update') ?
                    '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-lmsEditUser-modal' . $user->id . '"><i class="mdi mdi-square-edit-outline"></i></a>' : '';

                $deleteBtn = auth()->user()->hasPermission('users.delete') ?
                    '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $user->id . '"><i class="mdi mdi-delete"></i></a>' : '';

                return $editBtn . $deleteBtn . $editModal . $deleteModal;
            })
            ->rawColumns(['profile', 'status', 'action']) // important to render HTML
            ->make(true);
    }
}
