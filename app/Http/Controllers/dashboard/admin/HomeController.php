<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function home() {
        return view('dashboard.home');
    }
    
    public function analytics() {
        return view('dashboard.home.analytics');
    }

    public function adminProfile() {

        $user = auth()->user();
        if (!$user) {
            abort(404, 'User not found');
        }
        return view('dashboard.home.profile')->with(compact('user'));
    }
    
    public function adminUpdate(Request $request)
    {
        $user = auth()->user();
        $existing_image = base_path($user->image);
        if($request->file('profile_image')){
            if(File::exists($existing_image)){
                File::delete($existing_image);
            }
            $file = $request->file('profile_image');
            $file_name = $file->getClientOriginalName();
            $image_path = $file->storeAs('public/uploads/images/Users', $file_name, 'public_folder'); 
        }  
        $updated = $user->update([
            'name' => $request->input('name')?: $user->name,
            'email' => $request->input('email')?: $user->email,
            'phone_number' => $request->input('phone_number')?: $user->phone_number,
            'profile_image' => $request->file('profile_image')?$image_path:$user->profile_image,
        ]);
        if($updated){
            return redirect()->back()->with(['message' => 'Successfully updated']);
        }
    }
}
