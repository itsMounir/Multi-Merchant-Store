<?php

use App\Http\Controllers\Api\V1\Users\Auth\{
    CreateAccountController,
    LoginController,
};
use Illuminate\Support\Facades\Route;


Route::prefix('users/auth/')->group(function () {

    Route::post('account/create', [CreateAccountController::class, 'create']);
    Route::post('login', [LoginController::class, 'create']);
    Route::post('logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.user']);
});
