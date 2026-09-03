<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate']);
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'store']);
    Route::get('verify-email', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
    Route::post('verify-email', [AuthController::class, 'verifyEmail'])->name('email.verify');
    Route::post('verify-email/resend', [AuthController::class, 'resendVerificationCode'])->name('email.verify.resend');

    Route::get('forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendOtp'])->name('password.email');

    Route::get('verify-otp', [\App\Http\Controllers\ForgotPasswordController::class, 'showVerifyOtpForm'])->name('password.verify.form');
    Route::post('verify-otp', [\App\Http\Controllers\ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');

    Route::get('reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('todos', TodoController::class)->except(['show']);
    Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
    Route::post('todos/{id}/restore', [TodoController::class, 'restore'])->name('todos.restore');
});
