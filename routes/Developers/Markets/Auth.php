<?php

use App\Http\Controllers\Api\V1\Markets\Auth\{
    LoginController,
    RegisterController
};

Route::prefix('markets/')->group(function () {
    // Auth routes
    Route::get('/register', [RegisterController::class, 'create']); // get register page data
    Route::post('/register', [RegisterController::class, 'store']); // register a new market
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'active','type.market']);
});




