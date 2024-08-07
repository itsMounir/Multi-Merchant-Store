<?php

use App\Http\Controllers\Api\V1\Users\{
    OfferController,
};
use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;



Route::prefix('users/offer')->middleware([
    'auth:sanctum',
    'type.user',
    'isOnline',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::get('list', [OfferController::class, 'index']);
    Route::get('info/{id}', [OfferController::class, 'show']);
    Route::post('create', [OfferController::class, 'create']);
    Route::post('update/{id}', [OfferController::class, 'update']);
    Route::delete('delete/{id}', [OfferController::class, 'destroy']);
});
