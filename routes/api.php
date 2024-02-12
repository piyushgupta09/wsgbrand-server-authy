<?php

use Illuminate\Support\Facades\Route;
use Fpaipl\Authy\Http\Coordinators\AuthCoordinator;
use Fpaipl\Authy\Http\Coordinators\NotificationCoordinator;

Route::middleware(['api', 'throttle:60,1'])->prefix('api')->name('api.')->group(function () {

    // 1. Auth
    if (config('authy.registeration.website')) {
        Route::post('/register', [AuthCoordinator::class, 'register']);
    }
    Route::post('/send-login-otp', [AuthCoordinator::class, 'sendLoginOtp']);
    Route::post('/login', [AuthCoordinator::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthCoordinator::class, 'logout']);
        Route::post('email/verification', [AuthCoordinator::class, 'emailVerification']);
        Route::post('email/verify', [AuthCoordinator::class, 'verifyOtp']);
        Route::post('/validate-session', [AuthCoordinator::class, 'validateSession']);

        Route::middleware('verified')->group(function () {

            // 2. Profile & Password
            Route::post('update-user', [AuthCoordinator::class, 'updateUser']);
            Route::post('update-password', [AuthCoordinator::class, 'updateNewPassword']);
            Route::get('user-profile', [AuthCoordinator::class, 'userProfile']);
            Route::post('upload-profile-image', [AuthCoordinator::class, 'updateProfileImage']);
            // 3. Notifications
            Route::get('notifications/pusher', [NotificationCoordinator::class, 'pusherAuth']);
            Route::get('notifications', [NotificationCoordinator::class, 'index']);
            Route::get('notifications/unread', [NotificationCoordinator::class, 'unread']);
            Route::post('notifications/read', [NotificationCoordinator::class, 'markAllRead']);
            Route::post('notifications/{notification}/read', [NotificationCoordinator::class, 'markRead']);
        });

    });

});
