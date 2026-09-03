@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <div class="card auth-card">
        <h2 class="auth-heading">Verify your email</h2>
        <p class="auth-subtitle">Enter the 6-digit code from your welcome email to activate your account.</p>

        <form method="POST" action="{{ route('email.verify') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly>
            </div>

            <div class="field">
                <label for="code">Verification code</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Verify email</button>
            </div>
        </form>

        <form method="POST" action="{{ route('email.verify.resend') }}" class="auth-footer">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="btn btn-secondary btn-sm">Send a new code</button>
        </form>
    </div>
@endsection
