<?php

use App\Http\Controllers\Api\V1\Markets\Auth\{
    LoginController,
    RegisterController,
    VerificationController
};

Route::prefix('markets/')->group(function () {
    // auth routes
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'active','type.market']);
});




