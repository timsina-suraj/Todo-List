@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="card auth-card">
        <h2 class="auth-heading">Forgot Password</h2>
        <p class="auth-subtitle">Enter your email and we'll send you an OTP.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ request('email', old('email')) }}" required autofocus>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Send OTP</button>
            </div>
        </form>

        <div class="auth-footer">
            Remember your password? <a href="{{ route('login') }}">Log in</a>
        </div>
    </div>
@endsection
