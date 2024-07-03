<?php

use App\Http\Controllers\Api\V1\Users\StatisticsController;
use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;


Route::prefix('users/statistic')->middleware([
    'auth:sanctum',
    'type.user',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::get('bills', [StatisticsController::class, 'getBillStatistics']);
    Route::get('subscribers', [StatisticsController::class, 'getUsersStatistics']);
    Route::get('users-with-bills', [StatisticsController::class, 'getUsersWithBillsStatistics']);
});
