<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $userExists = User::where('email', $request->email)->exists();

        if ($userExists) {
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            Otp::updateOrCreate(
                ['email' => $request->email],
                [
                    'otp' => $otpCode,
                    'expires_at' => now()->addMinutes(5)
                ]
            );

            Mail::to($request->email)->send(new OtpMail($otpCode));
        }

        return redirect()->route('password.verify.form', ['email' => $request->email])
                         ->with('status', 'If an account with that email exists, we have sent an OTP.');
    }

    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->query('email') ?? $request->session()->get('password_reset_email');

        return view('auth.verify-otp', ['email' => $email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $otpRecord = Otp::where('email', $request->email)
                        ->where('otp', $request->otp)
                        ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid OTP.'])->withInput($request->all());
        }

        if (now()->isAfter($otpRecord->expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.'])->withInput($request->all());
        }

        $request->session()->put('password_reset_email', $request->email);
        $request->session()->put('password_reset_verified', true);

        return redirect()->route('password.reset.form');
    }

    public function showResetPasswordForm(Request $request)
    {
        if (!$request->session()->get('password_reset_verified')) {
            return redirect()->route('password.verify.form', ['email' => $request->session()->get('password_reset_email')])
                ->withErrors(['otp' => 'Please verify the OTP before resetting your password.']);
        }

        return view('auth.reset-password', ['email' => $request->session()->get('password_reset_email')]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(6)->mixedCase()->numbers()->symbols()->uncompromised()
            ],
        ]);

        if (!$request->session()->get('password_reset_verified') || $request->session()->get('password_reset_email') !== $request->email) {
            return redirect()->route('password.verify.form', ['email' => $request->email])
                ->withErrors(['otp' => 'Please verify the OTP before resetting your password.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $otpRecord = Otp::where('email', $request->email)->first();

        if ($otpRecord) {
            $otpRecord->delete();
        }

        $request->session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('login')->with('status', 'Password reset successfully. Please log in.');
    }
}
