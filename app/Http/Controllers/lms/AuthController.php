<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Login Page
    public function loginPage()
    {
        if (Auth::guard('lms')->check()) {
            return redirect()->route('lms.dashboard.home');
        }
        return view('lms.auth.login');
    }

    public function login(Request $request)
    {
        Log::info('Login request received.', [
            'email' => $request->input('email'),
            'remember' => $request->boolean('remember'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|min:6',
            ]);
            Log::info('Validation passed.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed.', $e->errors());
            throw $e; // Let Laravel handle the redirect with error bag
        }

            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember');

            Log::info('Attempting login...', ['credentials' => $credentials]);

            if (Auth::guard('lms')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('lms')->user();

            $userRole = UserRole::where('user_id', $user->id)->first();

            if (!$userRole) {
                Auth::guard('lms')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return $request->ajax()
                    ? response()->json(['message' => 'No role assigned to this account.',
                    'csrf' => csrf_token() ], 403)
                    : back()->withErrors(['email' => 'No role assigned to this account.']);
                }

                $role = Role::find($userRole->role_id);

                if ($role && (strcasecmp($role->role_name, 'Super Admin') === 0 || strcasecmp($role->role_name, 'Admin') === 0)) {
                        return $request->ajax()
                            ? response()->json(['redirect' => route('lms.dashboard.home')])
                            : redirect()->route('lms.dashboard.home');
                    }


                $rolePermission = RolePermission::where('role_id', $userRole->role_id)->first();
                $permissions = $rolePermission ? json_decode($rolePermission->permission_ids, true) : [];

                if (empty($permissions)) {
                    Auth::guard('lms')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                return $request->ajax()
                    ? response()->json([
                        'message' => 'You don’t have permission to access the system. Please contact the administrator.',
                        'csrf' => csrf_token() 
                    ], 403)
                    : back()->withErrors(['email' => 'You don’t have permission to access the system. Please contact the administrator.']);

                }

                return $request->ajax()
                    ? response()->json(['redirect' => route('lms.dashboard.home')])
                    : redirect()->route('lms.dashboard.home');
                }

            if ($request->ajax()) {
                return response()->json(['message' => 'Invalid email or password.'], 401);
            }

            return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    // Dashboard redirection
    public function dashboard()
    {
        if (!Auth::guard('lms')->check()) {
            return redirect()->route('lms.login');
        }

        return redirect()->route('lms.dashboard.home');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('lms')->logout();

        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('lms.login')->with('success', 'Logged out successfully.');
    }
}
