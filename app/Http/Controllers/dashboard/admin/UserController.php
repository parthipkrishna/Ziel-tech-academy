<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index() {
        $user_main = User::with('roles')->latest()->get();
        $user = auth()->user();
        $userRole = $user->roles()->orderBy('roles.id')->first();
        if (!$userRole) {
            abort(403, 'You do not have a role assigned.');
        }
        $roles = Role::where('type', 'web')->where('status', 1)
            ->where('id', '>', $userRole->id)
            ->get();
        return view('dashboard.user.index')->with(compact('user_main','roles'));
    }

    public function create()
    {
        $user = auth()->user();
        $userRole = $user->roles()->orderBy('roles.id')->first();
        if (!$userRole) {
            abort(403, 'You do not have a role assigned.');
        }
        $roles = Role::where('type', 'web')->where('status', 1)
            ->where('id', '>', $userRole->id)
            ->get();
        return view('dashboard.user.add', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'profile_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif|max:2048',
        ]);
        try {
            $thumbnailImagePath = NULL;
            if ($request->hasFile('profile_image')) {
                $thumbnailImagePath = $request->file('profile_image')->store('uploads/images/Users', 'public');
            }
            $data = $request->all();
            $userId = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? NULL,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'profile_image' => $thumbnailImagePath,
                'type' => 'web',
                'status' => isset($data['status']) ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user_role= new UserRole();
            $user_role->user_id = $userId;
            $user_role->role_id = $data['user_role'];
            $success = $user_role->save();
            if($success){
                $message ='User added successfully ';
                return redirect()->route('admin.users.index')->with('message', 'Successfully Stored');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput($request->input());
        }
    }

    public function show($id)
    {
        // Code to display a specific resource
    }

    public function edit($id)
    {
        // Code to show a form for editing a resource
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->ajax()) {
            $user->status = $request->status;
            $user->save();
            return response()->noContent();
        }
        $existing_image = base_path($user->profile_image);
        if($request->file('profile_image')){
            if(File::exists($existing_image)){
                File::delete($existing_image);
            }
            $file = $request->file('profile_image');
           $thumbnailImagePath = $request->file('profile_image')->store('uploads/images/Users', 'public');
        }
        $updated = $user->update([
            'name' => $request->input('name')?: $user->name,
            'email' => $request->input('email')?: $user->email,
            'phone' => $request->input('phone')?: $user->phone,
            'status' =>  $status = $request->has('status') ? $request->input('status') : $user->status,
            'profile_image' => $request->file('profile_image')?$thumbnailImagePath:$user->profile_image,
        ]);
        $userRole = UserRole::where('user_id', $user->id)->first();
        if ($userRole) {
            $userRole->role_id = $request->input('user_role');
            $userRole->save();
        } else {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->input('user_role'),
            ]);
        }
        if($updated){
            return redirect()->back()->with(['message' => 'Successfully updated']);
        }
    }

    public function destroy($id)
    {
        $success = user::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }


    public function activate($id)
    {    
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->status = 1;
        $user->save();
        return response()->json(['message' => 'User activated successfully!', 'status' => $user->status]);
    }
}
