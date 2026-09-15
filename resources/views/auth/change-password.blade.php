@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
    <div class="card auth-card">
        <h2 class="auth-heading">Change Password</h2>
        <p class="auth-subtitle">Choose a new password for your account.</p>

        <form method="POST" action="{{ route('password.change') }}" data-auth-form="change-password" novalidate>
            @csrf

            <div class="field">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
            </div>

            <div class="field">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary" formnovalidate>Update Password</button>
            </div>
        </form>
    </div>
@endsection
