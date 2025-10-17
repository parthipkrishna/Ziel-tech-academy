<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\AuthMethod;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Import DB facade
use App\Models\UserVerification;
use App\Models\StudentDeviceInfo;

class AuthController extends Controller
{


    public function register(Request $request)
    {
        // Step 1: Validate Input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20|unique:users',
            'user_role' => 'required|integer|exists:roles,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }
    
        // Step 2: Fetch Role and Ensure It's Not "Student"
        $role = Role::find($request->user_role);
    
        if (!$role || strtolower($role->slug) === 'student') {
            return response()->json([
                'status' => false,
                'message' => 'Student role is not allowed for this API.'
            ], 400);
        }
    
        // Step 3: Start Database Transaction
        DB::beginTransaction();
    
        try {
            // Create user
            $user = User::create([
                'name' => $request->first_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
            ]);
    
            // Assign role to user
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->user_role,
            ]);
    
            // Create verification entry
            UserVerification::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_email_verified' => false,
                'is_phone_verified' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            // Step 4: Commit Transaction if All Steps Succeed
            DB::commit();
    
            return response()->json([
                'message' => 'User registered successfully.',
                'status' => true,
            ], 200);
    
        } catch (\Exception $e) {
            // Step 5: Rollback Transaction if Any Error Occurss
            DB::rollBack();
    
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function loginWithPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',

            // Optional device info
            'device_id' => 'nullable|string',
            'device_type' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $validator->errors()->first()
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 400);
        }

        $user = Auth::user();

        // ✅ Handle device recording + is_blocked flag logic
        $this->handleStudentDevice($user, $request);

        $accessToken = $user->createToken('AuthToken')->plainTextToken;
        $refreshToken = $user->createToken('RefreshToken')->plainTextToken;

        AuthMethod::firstOrCreate(
            ['user_id' => $user->id, 'auth_type' => 'loginWithPassword'],
            ['auth_value' => $accessToken]
        );

        $subacriptions = $user->studentProfile->subscriptions;
        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'user' => $user,
            'student' => $user->studentProfile,
            'subcription' => $subacriptions,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);
    }

    public function loginWithSocialMedias(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'provider' => 'required|string',
                'token' => 'required|string',
                'email' => 'required|string|email',
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',

                // Optional device info
                'device_id' => 'nullable|string',
                'device_type' => 'nullable|string',
                'device_name' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'messages' => $validator->errors()->first()
                ], 400);
            }

            $socialUser = (object) [
                'email' => $request->email,
                'name' => $request->name,
                'phone' => $request->phone ?? null
            ];

            $user = User::where('email', $socialUser->email)->first();

            if ($user) {
                $user->update([
                    'name' => $socialUser->name ?? $user->name,
                    'phone' => $socialUser->phone ?? $user->phone
                ]);
            } else {
                $defaultPassword = $socialUser->email . Str::random(8) . '!@#123';
                $user = User::create([
                    'email' => $socialUser->email,
                    'password' => bcrypt($defaultPassword),
                    'name' => $socialUser->name,
                    'phone' => $socialUser->phone
                ]);
            }

            AuthMethod::firstOrCreate(
                ['user_id' => $user->id, 'auth_type' => $request->provider],
                ['auth_value' => $request->token]
            );

            // ✅ Handle device recording + is_blocked flag logic
            $this->handleStudentDevice($user, $request);

            $accessToken = $user->createToken('AuthToken')->plainTextToken;
            $refreshToken = $user->createToken('RefreshToken')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'user' => $user,
                'auth_type' => $request->provider,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function refreshToken(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first(), 'status' => false], 422);
            }

            $refreshToken = $request->input('refresh_token');

            // Find the user associated with the refresh token
            $tokenRecord = DB::table('personal_access_tokens')
                ->where('id', $refreshToken)
                ->first();

            if (!$tokenRecord) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid refresh token.',
                ], 400);
            }

            // Invalidate the used refresh token
            DB::table('personal_access_tokens')
                ->where('id', $refreshToken)
                ->delete();

            // Generate new tokens for the user
            $user = User::find($tokenRecord->tokenable_id);
            $accessToken = $user->createToken('AuthToken')->plainTextToken;
            $newRefreshToken = $user->createToken('RefreshToken')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Token refreshed successfully.',
                'access_token' => $accessToken,
                'refresh_token' => $newRefreshToken,
            ], 200);
        } catch (\Exception $e) {
            // Handle the exception, for example log it
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
    
    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        // Delete all tokens (logout user from all devices)
        $deletedTokensCount = $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful.'
        ], 200);
    }

    private function handleStudentDevice($user, Request $request)
    {
        if (!$request->device_id) return;
        $studentId = $user->studentProfile?->id;

        $existingDevice = StudentDeviceInfo::where('student_id', $studentId)
            ->where('device_id', $request->device_id)
            ->first();

        if (!$existingDevice) {
            $deviceCount = StudentDeviceInfo::where('student_id', $studentId)->count();

            if ($deviceCount >= 1) {

                if ($user->studentProfile) {
                    $user->studentProfile->update(['is_device_blocked' => true]);
                }

                // Store unapproved device info
                StudentDeviceInfo::create([
                    'student_id' => $studentId,
                    'device_id' => $request->device_id,
                    'device_type' => $request->device_type,
                    'device_name' => $request->device_name,
                    'ip_address' => $request->ip(),
                    'browser' => $request->userAgent(),
                    'is_approved' => false,
                ]);
            } else {
                // ✅ First-time approved device
                StudentDeviceInfo::create([
                    'student_id' => $studentId,
                    'device_id' => $request->device_id,
                    'device_type' => $request->device_type,
                    'device_name' => $request->device_name,
                    'ip_address' => $request->ip(),
                    'browser' => $request->userAgent(),
                    'is_approved' => true,
                ]);
            }

            // Additional logic: if more than 2 devices, mark studentProfile as blocked
            $totalDevices = StudentDeviceInfo::where('student_id', $studentId)->count();
            if ($totalDevices > 2 && $user->studentProfile) {
                $user->studentProfile->update(['is_device_blocked' => true]);
            }
        }
    }
}
