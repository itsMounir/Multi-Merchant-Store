<?php


use App\Http\Controllers\Api\V1\Markets\{
    SuppliersController,
    ProductsController,
    BillsController,
    GoalsController,
    MarketsController,
    CategoriesController
};

Route::prefix('markets/')->middleware(['auth:sanctum', 'active', 'type.market'])->group(function () {

    // Route for show suppliers categories
    Route::get('supplier-categories',[CategoriesController::class,'index']);

    // Routes for Suppliers-related actions
    Route::apiResource('suppliers', SuppliersController::class)->only(['index', 'show']);

    // Routes for Products-related actions
    Route::apiResource('products', ProductsController::class)->only(['index', 'show']);

    // Routes for Bills-related actions
    Route::resource('bills', BillsController::class)->except('edit');

    // Routes for Goals-related actions
    Route::get('goals', [GoalsController::class,'index']);

    // Route for show market profile
    Route::get('markets/{market}',[MarketsController::class,'show']);
});
