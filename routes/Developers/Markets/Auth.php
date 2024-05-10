<?php

use App\Http\Controllers\Api\V1\Markets\Auth\{
    LoginController,
    RegisterController,
    ForgetPasswordController
};
use App\Enums\TokenAbility;

Route::prefix('markets/auth/')->group(function () {
    // Auth routes
    Route::get('register', [RegisterController::class, 'create']); // get register page data
    Route::post('register', [RegisterController::class, 'store']); // register a new market
    Route::post('login', [LoginController::class, 'create']);

    Route::get('logout', [LoginController::class, 'destroy'])
        ->middleware([
            'auth:sanctum',
            'ability:' . TokenAbility::ACCESS_API->value,
            'active',
            'type.market'
        ]);


    Route::get('refresh-token', [LoginController::class, 'refreshToken'])
        ->middleware([
            'auth:sanctum',
            'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
        ]);

    Route::post('forget-password', [ForgetPasswordController::class, 'forgetPassword']);
    Route::post('verify-code', [ForgetPasswordController::class, 'verifyCode']);
    Route::post('reset-password', [ForgetPasswordController::class, 'resetPassword']);

});




