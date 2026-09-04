@extends('layouts.app')

@section('title', 'Register - Todo List')

@section('content')
<div class="card auth-card">
    <h2 class="auth-heading">Create Account</h2>
    <p class="auth-subtitle">Start organizing your day with less friction.</p>

    <form method="POST" action="{{ route('register') }}" data-auth-form="register" novalidate>
        @csrf

        <div class="field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
        </div>

        <div class="field">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="actions auth-actions">
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </div>
        <p class="auth-footer">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
    </form>
</div>
@endsection
