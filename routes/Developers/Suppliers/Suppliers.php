<?php

use App\Http\Controllers\Api\V1\Suppliers\{
    ProductSuppliersController,
    SupplierContoller,
    BillController,
    ReportController,
    ForgetPassword,
    ResetPassword
};

    Route::get('categories',[SupplierContoller::class,'categories_supplier']);
    Route::post('forget/password',[ForgetPassword::class,'forgetPassword']);
    Route::post('verifyCode',[ResetPassword::class,'verifyCode']);
    Route::post('reset/password',[ResetPassword::class,'resetPassword']);


Route::middleware(['auth:sanctum', 'type.supplier','active'])->group(function () {
    Route::get('shit',function () {
        return response()->json('shit');
    });

    Route::apiResource('suppliers',ProductSuppliersController::class);

    Route::post('is_available/{id}',[ProductSuppliersController::class,'is_available']);

    Route::get('available/{id}',[ProductSuppliersController::class,'get_product_available_or_Not_available']);

    Route::post('offer/{id}',[ProductSuppliersController::class,'offer']);

    Route::post('update/{id}',[ProductSuppliersController::class,'update']);

/**============================================================================================================ */


    Route::apiResource('products',SupplierContoller::class)->only(['index']);

    Route::get('personal/data',[SupplierContoller::class,'Personal_Data']);

    Route::post('update/name',[SupplierContoller::class,'edit_name']);

    Route::post('update/Distribution',[SupplierContoller::class,'updateDistributionLocations']);

    Route::post('add/discount',[SupplierContoller::class,'add_Discount']);



/**============================================================================================================ */

    Route::apiResource('bills',BillController::class);

    Route::post('update/bill/{bill}',[BillController::class,'update']);

    Route::post('reject/{id}',[BillController::class,'reject']);

    //Route::post('accept/{id}',[BillController::class,'accept']);

    Route::post('recive/{id}',[BillController::class,'recive']);

    Route::post('refuse/{id}',[BillController::class,'Refused']);

    /**============================================================================================================ */

    Route::get('reports',[ReportController::class,'reports']);




});
