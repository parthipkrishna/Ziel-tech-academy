<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Session};
use App\Models\User;

class AuthController extends Controller
{
    public function loginPage()
    {

        // If already logged in via 'admin' guard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        // Retrieve email from session (admin-specific)
        $email = session('admin_email', '');

        return view('Auth.login', compact('email'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        $remember = $request->boolean('remember');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials.'], 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Incorrect password.'], 401);
        }

        // Ensure only admin (web-dashboard) users can log in
        if ($user->type !== 'web') {
            return response()->json(['message' => 'Unauthorized user type.'], 403);
        }

        // Log in via 'admin' guard
        Auth::guard('admin')->login($user, $remember);

        // Store email in session if remember is true
        if ($remember) {
            session(['admin_email' => $credentials['email']]);
        } else {
            session()->forget('admin_email');
        }

        return response()->json(['redirect' => route('admin.dashboard')]);
    }

    public function dashboard()
    {
        
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login.page');
        }

        $user = Auth::guard('admin')->user();

        // If user type is wrong, logout
        if ($user->type !== 'web') {
            Auth::guard('admin')->logout();
            Session::forget('admin_email');

            return redirect()->route('admin.login.page')->withErrors(['email' => 'Access denied.']);
        }
        return redirect()->route('admin.users.index');
    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Session::forget('admin_email');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.page')->with('success', 'Logged out successfully.');
    }
}
