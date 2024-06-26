<?php


use App\Http\Controllers\Api\V1\Markets\{
    SuppliersController,
    ProductsController,
    BillsController,
    GoalsController,
    MarketsController,
    StartingPageController
};
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Markets\NotificationsController;

Route::prefix('markets/')->middleware([
    'auth:sanctum', // Authentication middleware
    'abilities:' . TokenAbility::ACCESS_API->value, // Custom ability check
    'active', // Other custom middleware
    'type.market', // Another custom middleware
])->group(function () {

    // Routes for Suppliers-related actions
    Route::apiResource('suppliers', SuppliersController::class)->only(['index', 'show']);


    // Route for starting page data
    Route::get('starting-page', StartingPageController::class);


    // Routes for Products-related actions
    Route::apiResource('products', ProductsController::class)->only(['index', 'show']);

    // Routes for Bills-related actions
    Route::resource('bills', BillsController::class)->except('edit');

    // Routes for show the achieved goals by authenticated market
    Route::get('goals', GoalsController::class);

    // Route for show market profile and update it.
    Route::get('renew-subscription', [MarketsController::class, 'sendRenewSubscriptionRequest']);
    Route::apiResource('markets', MarketsController::class)->only(['show', 'update']);

    Route::apiResource('notifications', NotificationsController::class)->only(['index', 'show']);
});
