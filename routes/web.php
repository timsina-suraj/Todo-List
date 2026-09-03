<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\PreventAuthenticatedPageCaching;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('todos.index')
        : redirect()->route('login');
});

Route::get('login', [AuthController::class, 'login'])
    ->middleware(PreventAuthenticatedPageCaching::class)
    ->name('login');

Route::middleware(['guest', PreventAuthenticatedPageCaching::class])->group(function () {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'store']);
    Route::get('verify-email', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
    Route::post('verify-email', [AuthController::class, 'verifyEmail'])->name('email.verify');
    Route::post('verify-email/resend', [AuthController::class, 'resendVerificationCode'])->name('email.verify.resend');

    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');

    Route::get('verify-otp', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('password.verify.form');
    Route::post('verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');

    Route::get('reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::middleware(['auth', PreventAuthenticatedPageCaching::class])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('todos', TodoController::class)->except(['show']);
    Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
    Route::post('todos/{id}/restore', [TodoController::class, 'restore'])->name('todos.restore');
});
