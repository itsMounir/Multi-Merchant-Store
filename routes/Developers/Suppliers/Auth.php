<?php

use App\Http\Controllers\Api\V1\Suppliers\Auth\{
    LoginController,
    RegisterController,
    VerificationController
};
use App\Http\Controllers\Api\V1\Suppliers\{
    ProductSuppliersController,
    SupplierContoller,
    BillController,
    ReportController
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
    Route::post('recive/{id}',[BillController::class,'recive']);
    Route::post('is_available/{id}',[ProductSuppliersController::class,'is_available']);
    Route::get('Bill_Recived',[ReportController::class,'Paid_Bill']);
    Route::get('market',[ReportController::class,'getMarketsCount']);
    Route::get('avg',[ReportController::class,'getAverageBillPrice']);
    Route::get('price/product/delivery',[ReportController::class,'getDeliveredProductPrice']);
    Route::get('available',[ProductSuppliersController::class,'get_product_available']);
    Route::get('personal/data',[SupplierContoller::class,'Personal_Data']);

});
