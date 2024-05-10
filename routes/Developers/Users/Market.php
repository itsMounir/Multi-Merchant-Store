<?php

use App\Http\Controllers\api\v1\users\MarketCategoryController;
use App\Http\Controllers\Api\V1\Users\MarketUserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users/market/')->middleware('auth:sanctum')->group(function () {

    Route::get('get', [MarketUserController::class, 'index']); // get filtered market users {activated- deactivated - baned}
    Route::get('profile/{id}', [MarketUserController::class, 'show']); // get user profile
    Route::post('activate/{id}', [MarketUserController::class, 'activateUser']); // activate market user
    Route::post('ban/{id}', [MarketUserController::class, 'banUser']); // ban market user
    Route::put('profile/edit/{id}', [MarketUserController::class, 'update']); // Edit user profile
    Route::get('with-bills/{id}', [MarketUserController::class, 'userWithBills']); // get user with his bills

    Route::get('category', [MarketCategoryController::class, 'index']); // get market categories
    Route::post('category', [MarketCategoryController::class, 'store']); // add market category
    Route::put('category/{id}', [MarketCategoryController::class, 'update']); // edit market category
    Route::delete('category/{id}', [MarketCategoryController::class, 'destroy']); // delete market category

});
