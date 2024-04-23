<?php


use App\Http\Controllers\Api\V1\Markets\{
    SuppliersController,
    ProductsController,
    BillsController,
    GoalsController,
    MarketsController,
    StartingPageController
};

Route::prefix('markets/')->middleware(['auth:sanctum', 'active', 'type.market'])->group(function () {

    // Routes for Suppliers-related actions
    Route::apiResource('suppliers', SuppliersController::class)->only(['index', 'show']);


    // Route for starting page data
    Route::get('starting-page', StartingPageController::class);


    // Routes for Products-related actions
    Route::apiResource('products', ProductsController::class)->only(['index', 'show']);

    // Routes for Bills-related actions
    Route::resource('bills', BillsController::class)->except('edit');

    // Routes for show the achieved goals by aurhenticated market
    Route::get('goals', GoalsController::class);

    // Route for show market profile
    Route::get('markets/{market}', [MarketsController::class, 'show']);
});
