<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('todos.index');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password) && ! $user->email_verified_at) {
            if (! $user->email_verification_code || ! $user->email_verification_code_expires_at?->isFuture()) {
                $this->sendVerificationCode($user);
            }

            return redirect()->route('email.verify.form', ['email' => $user->email])
                ->with('status', 'Please verify your email before signing in.');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/todos')->with('status', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->sendVerificationCode($user);

        return redirect()->route('email.verify.form', ['email' => $user->email])
            ->with('status', 'Your account was created. Check your email for the verification code.');
    }

    /**
     * Show the email verification form.
     */
    public function showEmailVerificationForm(Request $request)
    {
        return view('auth.verify-email', [
            'email' => $request->query('email', old('email', '')),
        ]);
    }

    /**
     * Verify a user's email address.
     */
    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || $user->email_verification_code !== $validated['code'] || ! $user->email_verification_code_expires_at?->isFuture()) {
            return back()->withErrors([
                'code' => 'That verification code is invalid or has expired.',
            ])->withInput();
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_code_expires_at' => null,
        ])->save();

        return redirect()->route('login')->with('status', 'Email verified. You can now sign in.');
    }

    /**
     * Send a fresh verification code.
     */
    public function resendVerificationCode(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && ! $user->email_verified_at) {
            $this->sendVerificationCode($user);
        }

        return redirect()->route('email.verify.form', ['email' => $validated['email']])
            ->with('status', 'If the account needs verification, a new code has been sent.');
    }

    private function sendVerificationCode(User $user): void
    {
        $user->forceFill([
            'email_verification_code' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'email_verification_code_expires_at' => now()->addMinutes(5),
        ])->save();

        Mail::to($user)->send(new WelcomeMail($user, $user->email_verification_code));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Logged out successfully.');
    }
}
