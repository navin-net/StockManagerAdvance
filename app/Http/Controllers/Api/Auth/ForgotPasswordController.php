<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Mail, RateLimiter};
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255']);
        $email = strtolower($data['email']);
        $key = 'otp-send:' . hash('sha256', $email);
        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw ValidationException::withMessages(['email' => 'Please wait before requesting another code']);
        }
        RateLimiter::hit($key, 120);
        if (User::where('email', $email)->exists()) {
            $otp = (string) random_int(100000, 999999);
            DB::transaction(function () use ($email, $otp) {
                User::where('email', $email)->lockForUpdate()->firstOrFail();
                DB::table('password_otps')->where('email', $email)->delete();
                DB::table('password_otps')->insert([
                    'email' => $email, 'otp' => Hash::make($otp),
                    'expires_at' => now()->addMinutes(5), 'last_sent_at' => now(),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            });
            Mail::raw('Your password reset code is ' . $otp . '. It expires in five minutes.', function ($message) use ($email) {
                $message->to($email)->subject('Password reset code');
            });
        }
        return response()->json(['status' => true, 'message' => 'If that account exists, a reset code has been sent']);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255', 'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);
        $email = strtolower($data['email']);
        $key = 'otp-reset:' . hash('sha256', $email);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            DB::table('password_otps')->where('email', $email)->delete();
            throw ValidationException::withMessages(['otp' => 'Too many attempts. Request a new code later']);
        }
        RateLimiter::hit($key, 600);
        DB::transaction(function () use ($data, $email) {
            $user = User::where('email', $email)->lockForUpdate()->first();
            $record = DB::table('password_otps')->where('email', $email)->lockForUpdate()->first();
            if (!$user || !$record || now()->greaterThan($record->expires_at) || !Hash::check($data['otp'], $record->otp)) {
                throw ValidationException::withMessages(['otp' => 'Invalid or expired code']);
            }
            $user->forceFill(['password' => Hash::make($data['password']), 'remember_token' => null])->save();
            $user->tokens()->delete();
            DB::table('password_otps')->where('email', $email)->delete();
        });
        RateLimiter::clear($key);
        return response()->json(['status' => true, 'message' => 'Password reset successful']);
    }
}
