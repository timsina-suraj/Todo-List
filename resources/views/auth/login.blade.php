@extends('layouts.app')

@section('title', 'Login - Todo List')

@section('content')
<div class="card auth-card">
    <h2 class="auth-heading">Welcome Back</h2>
    <p class="auth-subtitle">Log in to manage your tasks.</p>

    <form method="POST" action="{{ route('login') }}" data-auth-form="login" novalidate>
        @csrf

        <div class="field">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        </div>

        <div class="field">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <label for="password" style="margin-bottom: 0;">Password</label>
                <a href="#" onclick="event.preventDefault(); const email = document.getElementById('email').value; window.location.href = '{{ route('password.request') }}' + (email ? '?email=' + encodeURIComponent(email) : '');" style="font-size: 0.85rem; color: var(--primary); text-decoration: none;">Forgot Password?</a>
            </div>
            <input type="password" id="password" name="password" required style="margin-top: 0.35rem;" autocomplete="current-password">
        </div>

        <div class="actions auth-actions">
            <button type="submit" class="btn btn-primary">Log In</button>
        </div>
        <p class="auth-footer">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
    </form>
</div>
@endsection
