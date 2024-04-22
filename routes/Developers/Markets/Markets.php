<?php


use App\Http\Controllers\Api\V1\Markets\{
    SuppliersController,
    ProductsController,
    BillsController,
    GoalsController,
    MarketsController
};

Route::prefix('markets/')->middleware(['auth:sanctum', 'active', 'type.market'])->group(function () {

    // Routes for Suppliers-related actions
    Route::apiResource('suppliers', SuppliersController::class)->only(['index', 'show']);
    // get suppliers categories
    Route::get('supplier-categories', [SuppliersController::class, 'getCategories']);


    // Routes for Products-related actions
    Route::apiResource('products', ProductsController::class)->only(['index', 'show']);

    // Routes for Bills-related actions
    Route::resource('bills', BillsController::class)->except('edit');

    // Routes for show the achieved goals by aurhenticated market
    Route::get('goals', [GoalsController::class, 'getAchievedGoals']);

    // Route for get sliders offers
    Route::get('slider', [GoalsController::class, 'index']);

    // Route for show market profile
    Route::get('markets/{market}', [MarketsController::class, 'show']);
});
