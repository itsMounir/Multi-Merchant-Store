<?php

use App\Http\Controllers\Api\V1\Users\Auth\{
    LoginController,
    RegisterController,
    VerificationController
};

Route::prefix('users/')->group(function () {
    // auth routes
    Route::post('/register', [RegisterController::class, 'create']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.user']);
});


// user section
Route::middleware(['auth:sanctum', 'type.user'])->group(function () {
    //
});
