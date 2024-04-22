<?php

use App\Http\Controllers\Api\V1\Suppliers\{
    ProductSuppliersController,
    SupplierContoller,
    BillController,
    ReportController
};

Route::middleware(['auth:sanctum', 'type.supplier','active'])->group(function () {
    Route::get('shit',function () {
        return response()->json('shit');
    });

    Route::apiResource('suppliers',ProductSuppliersController::class);

    Route::post('update/price/{id}',[ProductSuppliersController::class,'update']);

    Route::post('is_available/{id}',[ProductSuppliersController::class,'is_available']);

    Route::get('available/{id}',[ProductSuppliersController::class,'get_product_available_or_Not_available']);

    Route::post('offer/{id}',[ProductSuppliersController::class,'offer']);

    Route::post('update_offer/{id}',[ProductSuppliersController::class,'update_offer']);

/**============================================================================================================ */


    Route::apiResource('products',SupplierContoller::class)->only(['index']);

    Route::get('personal/data',[SupplierContoller::class,'Personal_Data']);

/**============================================================================================================ */

    Route::apiResource('bills',BillController::class)->only(['index']);

    Route::post('update/{bill}',[BillController::class,'update']);

    Route::post('reject/{id}',[BillController::class,'reject']);

    Route::post('accept/{id}',[BillController::class,'accept']);

    Route::post('recive/{id}',[BillController::class,'recive']);

    /**============================================================================================================ */

    Route::get('Bill_Recived',[ReportController::class,'Paid_Bill']);

    Route::get('market',[ReportController::class,'getMarketsCount']);

    Route::get('avg',[ReportController::class,'getAverageBillPrice']);

    Route::get('price/product/delivery',[ReportController::class,'getDeliveredProductPrice']);




});
