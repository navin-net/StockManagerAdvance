<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{DB, Hash, Mail};

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $now = Carbon::now();
        $otpRecord = DB::table('password_otps')->where('email', $request->email)->first();

        // Cooldown: 2 minutes
        if ($otpRecord && $otpRecord->last_sent_at) {
            $diff = $now->diffInSeconds(Carbon::parse($otpRecord->last_sent_at));
            if ($diff < 120) {
                $seconds = 120 - $diff;
                return response()->json([
                    'status' => false,
                    'message' => "Please wait {$seconds} seconds before requesting a new OTP."
                ], 429);
            }
        }

        // Generate OTP
        $otp = random_int(100000, 999999);

        DB::table('password_otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => Hash::make($otp),
                'expires_at' => $now->copy()->addMinutes(5),
                'last_sent_at' => $now,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => [
                'required',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$/'
            ]
        ]);

        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json(['status'=>false,'message'=>'OTP not found'], 400);
        }

        if (now()->greaterThan(Carbon::parse($record->expires_at))) {
            return response()->json(['status'=>false,'message'=>'OTP expired'], 400);
        }

        if (!Hash::check($request->otp, $record->otp)) {
            return response()->json(['status'=>false,'message'=>'Invalid OTP'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_otps')->where('email', $request->email)->delete();

        return response()->json(['status'=>true,'message'=>'Password reset successful']);
    }


}
