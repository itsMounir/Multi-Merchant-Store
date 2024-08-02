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

    Route::get('bills', [StatisticsController::class, 'getBillStatistics']);
    Route::get('market-users', [StatisticsController::class, 'getMarketUsersStatistics']);
    //Route::get('subscriptions', [StatisticsController::class, 'getMarketSubscriptionsStatistics']);
    Route::get('users-with-bills', [StatisticsController::class, 'getUsersWithBillsStatistics']);
});
