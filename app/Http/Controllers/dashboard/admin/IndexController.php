<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;

class IndexController extends Controller
{

    public function home() {
        return view('dashboard.home');
    }

    public function analytics() {
        return view('dashboard.analytics');
    }

    public function adminProfile() {
        $user = auth()->user();
        if (!$user) {
            abort(404, 'User not found');
        }
        return view('dashboard.home.profile')->with(compact('user'));
    }

    public function adminUpdate(Request $request, $id) 
    {
        $user = User::find($id); 
        if (!$user) {
            return redirect()->back()->withErrors(['message' => 'User not found']);
        }        
        $existing_image = base_path($user->image);
        $thumbnailImagePath = null;
        if($request->file('profile_image')){
            if(File::exists($existing_image)){
                File::delete($existing_image);
            }
            $thumbnailImagePath = $request->file('profile_image')->store('uploads/images/Users', 'public');
        }
        $updated = $user->update([
            'name' => $request->input('name')?: $user->name,
            'email' => $request->input('email')?: $user->email,
            'phone' => $request->input('phone')?: $user->phone,
            'profile_image' => $request->file('profile_image')?$thumbnailImagePath:$user->profile_image,
        ]);
        if($updated){
            return redirect()->back()->with(['message' => 'Successfully updated']);
        }
    }
}
