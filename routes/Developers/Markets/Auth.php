<?php

use App\Http\Controllers\Api\V1\Auth\Markets\{
    LoginController,
    RegisterController,
    VerificationController
};

Route::prefix('markets/')->group(function () {
    // auth routes
    Route::post('/register', [RegisterController::class, 'create']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.market']);
});


// market section
Route::middleware(['auth:sanctum', 'type.market'])->group(function () {
    //
});

