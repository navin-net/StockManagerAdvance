<?php

namespace App\Http\Controllers\Shop\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Mail};
use App\Http\Controllers\Controller;
use App\Models\{PasswordOtp, User};
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showEmailForm()
    {
        return view('frontend.auth.forgot-password');
    }
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]
        );

        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your Password Reset OTP');
        });

        return redirect()->route('password.otp.form')
            ->with('email', $request->email);
    }
    public function showOtpForm()
    {

        return view('frontend.auth.verify-otp');
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $record = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Invalid or Expired OTP']);
        }

        session(['reset_email' => $request->email]);

        return redirect()->route('password.reset.form');
    }
    public function showResetForm()
    {
        return view('frontend.auth.reset-password');
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        PasswordOtp::where('email', $email)->delete();

        return redirect('/login')->with('success', 'Password Reset Successful');
    }
}
