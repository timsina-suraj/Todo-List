@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
    <div class="card auth-card">
        <h2 class="auth-heading">Verify OTP</h2>
        <p class="auth-subtitle">Enter the 6-digit OTP sent to your email to continue.</p>

        <form method="POST" action="{{ route('password.verify') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly>
            </div>

            <div class="field">
                <label for="otp">OTP</label>
                <input type="text" id="otp" name="otp" value="{{ old('otp') }}" required autofocus>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Verify OTP</button>
            </div>
        </form>
    </div>
@endsection
