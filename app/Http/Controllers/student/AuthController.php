<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function loginPage(){

        if (auth()->guard('student')->check()) {
            
        return redirect()->route('student.dashboard.home');
    }

        return view('student.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->has('remember');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials. Please check your email and password.'], 401);
        }

        // Check if user has Student role
        if (!$user->roles->contains('role_name', 'Student')) {
            return response()->json(['message' => 'You do not have access to this portal.'], 403);
        }

        // ✅ Ensure student exists and is enrolled
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student record not found.'], 403);
        }

        $isEnrolled = StudentEnrollment::where('student_id', $student->id)->exists();

        if (!$isEnrolled) {
            return response()->json(['message' => 'You are not enrolled in any course.'], 403);
        }

        // Login student
        Auth::guard('student')->login($user, $remember);

        $minutes = 60 * 24 * 30; // 30 days

        if ($remember) {
            Cookie::queue('student_email', $email, $minutes);
            Cookie::queue('student_password', $password, $minutes); 
        } else {
            Cookie::queue(Cookie::forget('student_email'));
            Cookie::queue(Cookie::forget('student_password'));
        }

        return response()->json(['redirect' => route('student.portal.analytics')]);
    }



    public function dashboard() {
        if(!auth()->user()){
            return redirect()->route('student.login');
        }
        else if(auth()->user()){
            return  redirect()->route('student.dashboard.home');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        Session::flush(); // Clear session data
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the token
    
        return redirect()->route('student.login')->with('success', 'Logged out successfully.');
    }

    public function resetpassword()
    {
        return view('student.reset_password.resetPassword');
    }
}

