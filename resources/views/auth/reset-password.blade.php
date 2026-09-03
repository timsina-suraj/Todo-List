@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="card auth-card">
        <h2 class="auth-heading">Reset Password</h2>
        <p class="auth-subtitle">Create a new password for your account.</p>

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly>
            </div>

            <div class="field">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
@endsection
