<?php

use App\Http\Controllers\Auth\Api\AuthenticatedSessionController;
use App\Http\Controllers\Auth\Api\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\Api\NewPasswordController;
use App\Http\Controllers\Auth\Api\PasswordResetLinkController;
use App\Http\Controllers\Auth\Api\RegisteredUserController;
use App\Http\Controllers\Auth\Api\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1']);

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth');
