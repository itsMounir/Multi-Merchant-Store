<?php

use App\Http\Controllers\Api\V1\Users\StatisticsController;
use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;


Route::prefix('users/statistic')->middleware([
    'auth:sanctum',
    'type.user',
    'isOnline',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::get('bills-inventory', [StatisticsController::class, 'getBillsInventory']);
    Route::get('week-bills-statistics', [StatisticsController::class, 'getBillStatisticsPerWeek']);
    Route::get('month-bills-statistics', [StatisticsController::class, 'getBillStatisticsPerMonth']);
    Route::get('users-count', [StatisticsController::class, 'getMarketsAndSuppliersCount']);

    Route::get('top-three-order', [StatisticsController::class, 'getTopThreeOrderingMarketsAndSuppliers']);
    Route::get('top-three-cancelling', [StatisticsController::class, 'getTopThreecancellingMarketsAndSuppliers']);
});
