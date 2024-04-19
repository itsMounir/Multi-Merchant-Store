<?php

use App\Http\Controllers\Api\V1\Suppliers\Auth\{
    LoginController,
    RegisterController,
    VerificationController
};
use App\Http\Controllers\Api\V1\Suppliers\{
    ProductSuppliersController,
    SupplierContoller,
    BillController
};

Route::prefix('suppliers/')->group(function () {
    // auth routes
    Route::post('/register', [RegisterController::class, 'create']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.supplier']);
});


// supplier section
Route::middleware(['auth:sanctum', 'type.supplier'])->group(function () {
    Route::get('shit',function () {
        return response()->json('shit');
    });

    Route::apiResource('suppliers',ProductSuppliersController::class);
    Route::get('product',[SupplierContoller::class,'index']);
    Route::post('price/{id}',[ProductSuppliersController::class,'update']);
    Route::get('bill',[BillController::class,'index']);
    Route::post('update/{id}',[BillController::class,'update']);
    Route::post('reject/{id}',[BillController::class,'reject']);
    Route::post('accept/{id}',[BillController::class,'accept']);

});
