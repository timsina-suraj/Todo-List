@extends('layouts.app')

@section('title', 'Login - Todo List')

@section('content')
<div class="card auth-card">
    <h2 class="auth-heading">Welcome Back</h2>
    <p class="auth-subtitle">Log in to manage your tasks.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Email Address</label>
            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="actions auth-actions">
            <button type="submit" class="btn btn-primary">Log In</button>
        </div>
        <p class="auth-footer">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
    </form>
</div>
@endsection
