<?php

use App\Http\Controllers\Api\V1\Users\Auth\{
    LoginController,
    RegisterController,
};
use App\Http\Controllers\Api\V1\Users\MarketUserController;
use App\Http\Controllers\Api\V1\Users\SupplierUserController;
use App\Models\Market;
use Illuminate\Support\Facades\Route;


Route::prefix('users/')->group(function () {

    Route::post('/register', [RegisterController::class, 'create']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.user']);
});
