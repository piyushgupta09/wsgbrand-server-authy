<?php

use Illuminate\Support\Facades\Route;
use Fpaipl\Authy\Http\Controllers\UserController;
use Fpaipl\Authy\Http\Controllers\LoginController;
use Fpaipl\Authy\Http\Controllers\ProfileController;
use Fpaipl\Authy\Http\Controllers\RegisterController;
use Fpaipl\Authy\Http\Controllers\VerificationController;
use Fpaipl\Authy\Http\Controllers\ResetPasswordController;
use Fpaipl\Authy\Http\Controllers\ForgotPasswordController;
use Fpaipl\Authy\Http\Controllers\ConfirmPasswordController;

Route::middleware(['web'])->group(function () {

    if (config('authy.registeration.server')) {
        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [RegisterController::class, 'register']);
    }

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('email/verify', [VerificationController::class, 'show'])->middleware('auth')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

    Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->middleware('auth')->name('password.confirm');
    Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm'])->middleware('auth');

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('profiles', [ProfileController::class, 'show'])->name('profiles.show');
    Route::post('profiles/update', [ProfileController::class, 'updateProfile'])->name('profiles.update');
    Route::post('profiles/image', [ProfileController::class, 'updateProfileImage'])->name('profiles.image');
    Route::post('profiles/remove-image', [ProfileController::class, 'removeProfileImage'])->name('profiles.remove-image');

    Route::resource('users', UserController::class)->withTrashed(['show']);
    
});

