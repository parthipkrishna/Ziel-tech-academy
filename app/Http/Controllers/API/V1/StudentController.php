<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AuthMethod;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\UserVerification;
use Carbon\Carbon;
use App\Models\UserOtp;
use App\Traits\HandlesDeviceInfo;
use Illuminate\Support\Facades\Storage;
use App\Mail\OtpMail;                 // <--- Add this
use Illuminate\Support\Facades\Mail; // Laravel Mail facade

class StudentController extends Controller
{
    use HandlesDeviceInfo;

    public function registerStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20|unique:users',
            'gender' => 'nullable|string|max:10|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'admission_date' => 'nullable|date',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Generate admission number
            $lastStudent = Student::latest('id')->first();
            $lastAdmissionNumber = $lastStudent ? (int) substr($lastStudent->admission_number, 3) : 0;
            $newAdmissionNumber = 'ADM' . str_pad($lastAdmissionNumber + 12, 4, '0', STR_PAD_LEFT);

            // Create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
            ]);

            // Create student profile
            $student = Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'admission_number' => $newAdmissionNumber,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'zip_code' => $request->zip_code,
                'profile_photo' => $request->profile_photo,
                'admission_date' => now(),
                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'status' => true,
            ]);

            // Create verification record
            $verification = UserVerification::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_email_verified' => false,
                'is_phone_verified' => false,
            ]);

            // Assign role
            $role_id = Role::whereRaw('UPPER(role_name) = ?', ['STUDENT'])->first();
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role_id->id ?? 5, // default student role
            ]);

            // Generate email OTP
            $emailOtp = $this->generateOtp($user);
            // Here you can send email OTP
            Mail::to($user->email)->send(new OtpMail($emailOtp));

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Student registered successfully.',
                'user_id' => $user->id,
                'email_verified' => $verification->is_email_verified,
                'phone_verified' => $verification->is_phone_verified,
                'email_otp' => $emailOtp, // only for testing, remove in production
            ], 200);
        } catch (\Exception $e) {
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

            'device_id' => 'required|string',
            'device_type' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $validator->errors()->first()
            ], 400);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 400);
        }

        $user = Auth::user();
        $user_role = $user->roles->first();

        if (!$user_role || strcasecmp($user_role->role_name, "STUDENT") !== 0) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Handle Student Device Info
        $this->handleStudentDevice($user, $request);

        // Generate tokens
        $accessToken = $user->createToken('AuthToken')->plainTextToken;
        $refreshToken = $user->createToken('RefreshToken')->plainTextToken;

        AuthMethod::firstOrCreate(
            ['user_id' => $user->id, 'auth_type' => "loginWithPassword"],
            ['auth_value' => $accessToken]
        );

        $user->load(['studentProfile.subscriptions']);
        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'user' => $user,
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

                'device_id' => 'required|string',
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

            if (empty($socialUser->email)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email address.'
                ], 400);
            }

            $user = User::where('email', $socialUser->email)->first();
            if ($user) {
                $user->update([
                    'name' => $socialUser->name ?? $user->name,
                    'phone' => $socialUser->phone ?? $user->phone
                ]);
            } else {
                $randomString = Str::random(8);
                $defaultPassword = $socialUser->email . $randomString . '!@#123';

                $user = User::create([
                    'email' => $socialUser->email,
                    'password' => bcrypt($defaultPassword),
                    'name' => $socialUser->name,
                    'phone' => $socialUser->phone
                ]);
            }

            $this->handleStudentDevice($user, $request);

            AuthMethod::firstOrCreate(
                ['user_id' => $user->id, 'auth_type' => $request->provider],
                ['auth_value' => $request->token]
            );

            $accessToken = $user->createToken('AuthToken')->plainTextToken;
            $refreshToken = $user->createToken('RefreshToken')->plainTextToken;

            $user->load(['studentProfile.subscriptions']);
            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'user' => $user,
                'auth_type' => $request->provider,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken
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
        $request->session()->flush();
        $deletedTokensCount = $request->user()->tokens()->delete();
        if ($deletedTokensCount === 0) {
            return response()->json([
                'status' => false,
                'error' => 'User was already logged out.'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Logout successful.'
        ], 200);
    }

    public function requestOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required_without:phone|string|email|max:255',
                'phone' => 'required_without:email|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'messages' => $validator->errors()->first()
                ], 400);
            }

            $user = User::where('email', $request->email)
                ->orWhere('phone', $request->phone)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            $otp = $this->generateOtp($user);

            if ($request->has('email')) {
                // Send OTP via email
                // Mail::to($request->email)->send(new OtpMail($otp));
            } elseif ($request->has('phone')) {
                // Send OTP via SMS
                // SmsService::send($request->phone, "Your OTP is: $otp");
            }

            return response()->json([
                'message' => 'OTP has been sent successfully.',
                'otp' => $otp,
                'status' => true
            ], 200);
        } catch (\Exception $e) {
            // Handle the exception, for example log it
            return response()->json([
                'status' => false,
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function verifySmsOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:20',
            'otp' => 'required|string|max:6',

            'device_id' => 'required|string',
            'device_type' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $validator->errors()->first()
            ], 400);
        }

        $user = User::where('phone', $request->phone)->firstOrFail();

        $userOtp = UserOtp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$userOtp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $userOtp->delete();

        UserVerification::where('user_id', $user->id)
            ->where('is_phone_verified', false)
            ->update(['is_phone_verified' => true]);

        $this->handleStudentDevice($user, $request);

        $accessToken = $user->createToken('AuthToken')->plainTextToken;
        $refreshToken = $user->createToken('RefreshToken')->plainTextToken;

        $user->load(['studentProfile.subscriptions']);
        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);
    }

    public function verifyEmailOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'otp' => 'required|string|max:6',

            'device_id' => 'required|string',
            'device_type' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $validator->errors()->first()
            ], 400);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $userOtp = UserOtp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$userOtp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $userOtp->delete();

        UserVerification::where('user_id', $user->id)
            ->where('is_email_verified', false)
            ->update(['is_email_verified' => true]);

        $this->handleStudentDevice($user, $request);

        $accessToken = $user->createToken('AuthToken')->plainTextToken;
        $refreshToken = $user->createToken('RefreshToken')->plainTextToken;

        $user->load(['studentProfile.subscriptions']);
        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);
    }

    private function generateOtp(User $user)
    {
        $otp = random_int(100000, 999999); // Generate a random 6-digit OTP
        $expiresAt = Carbon::now()->addMinutes(10); // OTP expires in 10 minutes

        UserOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        return $otp;
    }

    /**
     * Get logged-in student's profile.
     */
    public function show(Request $request)
    {
        try {
            $studentId = auth()->user()->student_id ?? null;

            if (!$studentId) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Student ID not found for this user',
                ], 404);
            }

            $student = Student::with('user')->find($studentId);

            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Profile not found',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data'   => $student,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update profile image.
     */
    public function updateProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            $student = Student::where('user_id', auth()->id())->first();

            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            // Delete old image if exists
            if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
                Storage::disk('public')->delete($student->profile_photo);
            }

            // Store new image
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $student->profile_photo = $path;
            $student->save();

            return response()->json([
                'status'  => true,
                'message' => 'Profile image updated successfully',
                'profile_photo_url' => $path,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update profile details (except email & phone).
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'nullable|string|max:255',
            'last_name'      => 'nullable|string|max:255',
            'date_of_birth'  => 'nullable|date',
            'gender'         => 'nullable|string|in:male,female,other',
            'address'        => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'zip_code'       => 'nullable|string|max:20',
            'guardian_name'  => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            DB::beginTransaction();

            $student = Student::where('user_id', auth()->id())->first();

            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            // Update allowed fields only
            $student->update($request->only([
                'first_name',
                'last_name',
                'date_of_birth',
                'gender',
                'address',
                'city',
                'state',
                'country',
                'zip_code',
                'guardian_name',
                'guardian_contact'
            ]));

            // Update User table `name` field
            $student->user->update([
                'name' => trim($student->first_name . ' ' . $student->last_name),
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Profile updated successfully',
                'data'    => $student->fresh(),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
