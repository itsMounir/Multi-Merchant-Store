<?php

use App\Http\Controllers\Api\V1\Markets\Auth\{
    LoginController,
    RegisterController,
    VerificationController
};
use App\Http\Controllers\Api\V1\Markets\{
    SuppliersController,
    ProductsController,
    BillsController
};

Route::prefix('markets/')->group(function () {
    // auth routes
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.market']);

    // market section
    Route::middleware(['auth:sanctum', 'type.market'])
        ->group(function () {
            Route::apiResource('suppliers',SuppliersController::class)->only(['index','show']);
            // Route::get('suppliers', [SuppliersController::class, 'index']);
            // Route::get('suppliers/{supplier}', [SuppliersController::class, 'show']);

            Route::get('products',[ProductsController::class,'index']);

            Route::resource('bills',BillsController::class);
            //Route::get('bills/old',BillsController::class);
           // Route::post('bills',[BillsController::class,'store']);
        });
});




