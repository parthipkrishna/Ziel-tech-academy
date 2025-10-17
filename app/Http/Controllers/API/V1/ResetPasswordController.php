<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Step 1: Request password reset OTP (public)
     */
    public function requestPasswordResetOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()->first()], 422);
        }

        $email = $request->email;

        // Prevent OTP abuse (1 per 60 seconds)
        $existing = DB::table('password_resets')->where('email', $email)->first();
        if ($existing && Carbon::parse($existing->updated_at)->diffInSeconds(now()) < 60) {
            return response()->json(['status' => false, 'error' => 'Please wait before requesting another OTP'], 429);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'reset_token' => null,
                'used' => false,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // TODO: Replace with actual mailer
        Mail::raw("Your OTP for password reset is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Password Reset OTP');
        });

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully to your email address.',
            'otp' => app()->environment('local') ? $otp : null, // visible only in local/dev
        ]);
    }

    /**
     * Step 2: Verify OTP and issue a reset token
     */
    public function verifyResetOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()->first()], 422);
        }

        $record = DB::table('password_resets')->where('email', $request->email)->first();

        if (
            !$record ||
            $record->otp !== $request->otp ||
            $record->used ||
            Carbon::parse($record->expires_at)->isPast()
        ) {
            return response()->json(['status' => false, 'error' => 'Invalid or expired OTP'], 400);
        }

        $resetToken = Str::random(64);

        DB::table('password_resets')
            ->where('email', $request->email)
            ->update([
                'reset_token' => $resetToken,
                'used' => true, // mark OTP as used
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully.',
            'reset_token' => $resetToken,
        ]);
    }

    /**
     * Step 3: Reset password using the token
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reset_token' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()->first()], 422);
        }

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('reset_token', $request->reset_token)
            ->first();

        if (
            !$record ||
            Carbon::parse($record->expires_at)->isPast() ||
            $record->used === false
        ) {
            return response()->json(['status' => false, 'error' => 'Invalid or expired reset token'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => false, 'error' => 'User not found'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        // delete record to prevent reuse
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password has been reset successfully.',
        ]);
    }

    /**
     * Step 4: Change password for authenticated users
     */
    public function changePasswordAuthenticated(Request $request)
    {
        try {
            // 🔍 Validate request data
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|confirmed|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                ], 422);
            }

            // 🔐 Check authentication
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'error' => 'Unauthorized. Please log in to continue.',
                ], 401);
            }

            // 🔑 Verify current password
            if (!password_verify($request->current_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Current password is incorrect.',
                ], 400);
            }

            // 🔄 Update password
            $user->password = bcrypt($request->new_password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully.',
            ], 200);
        } catch (\Throwable $e) {
            // 🧯 Handle unexpected exceptions
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong. Please try again later.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
