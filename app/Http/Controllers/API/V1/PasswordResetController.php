<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function requestPasswordResetOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()->first()], 422);
        }

        $email = $request->email;

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
                'expires_at' => $expiresAt,
                'used' => false,
                'reset_token' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // TODO: Replace with actual mail service in production
        Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your Password Reset OTP');
        });

        return response()->json(['status' => true, 'message' => 'OTP sent to email', 'otp' => $otp]);
    }

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
                'used' => true,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully',
            'reset_token' => $resetToken,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reset_token' => 'required',
            'password' => 'required|confirmed|min:6',
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

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['status' => true, 'message' => 'Password reset successful']);
    }
}
