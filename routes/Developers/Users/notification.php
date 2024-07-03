<?php

use App\Http\Controllers\Api\V1\Users\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;



Route::prefix('users/notification')->middleware([
    'auth:sanctum',
    'type.user',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::prefix('database')->group(function () {

        Route::get('index', [NotificationController::class, 'index']);
        Route::put('mark-as-read/{id}', [NotificationController::class, 'markAsRead']);
        Route::put('mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('delete/{id}', [NotificationController::class, 'destroy']);
    });
});
