<?php

use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Users\CouponController;

Route::prefix('users/coupon')->middleware([
    'auth:sanctum',
    'type.user',
    'isOnline',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::get('qq', function () {
        return "qq";
    });
    
    Route::get('index', [CouponController::class, 'index']);
    Route::post('store', [CouponController::class, 'store']);
    Route::put('active/{id}', [CouponController::class, 'active']);
    Route::put('deactive/{id}', [CouponController::class, 'deactive']);
    Route::delete('delete/{id}', [CouponController::class, 'destroy']);
});
