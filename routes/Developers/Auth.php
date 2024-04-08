<?php

use App\Http\Controllers\Api\V1\Auth\{
    LoginController,
    RegisterController,
    VerificationController
};

// auth routes
Route::post('/register', [RegisterController::class, 'create']);
Route::post('/login', [LoginController::class, 'create']);
Route::get('/logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');

