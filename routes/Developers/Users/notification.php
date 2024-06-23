<?php

use App\Http\Controllers\Api\V1\Users\NotificationController;
use Illuminate\Support\Facades\Route;



Route::prefix('users/notification')->middleware('auth:sanctum', 'type.user')->group(function () {

    Route::prefix('database')->group(function () {

        Route::get('index', [NotificationController::class, 'index']);
        Route::put('mark-as-read/{id}', [NotificationController::class, 'markAsRead']);
        Route::put('mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('delete/{id}', [NotificationController::class, 'destroy']);
    });
});
