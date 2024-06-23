<?php

use App\Http\Controllers\Api\V1\Users\MarketCategoryController;
use App\Http\Controllers\Api\V1\Users\MarketUserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users/market/')->middleware('auth:sanctum','type.user')->group(function () {

    Route::get('get', [MarketUserController::class, 'index']); // get filtered market users {activated- deactivated - baned}
    Route::get('search', [MarketUserController::class, 'search']); //search By store_name
    Route::get('profile/{id}', [MarketUserController::class, 'show']); // get user profile
    Route::post('activate/{id}', [MarketUserController::class, 'activate']); // activate market user
    Route::post('ban/{id}', [MarketUserController::class, 'ban']); // ban market user
    Route::put('profile/edit/{id}', [MarketUserController::class, 'update']); // Edit user profile
    Route::get('with-bills/{id}', [MarketUserController::class, 'userWithBills']); // get user with his bills

    Route::get('category', [MarketCategoryController::class, 'index']); // get market categories
    Route::post('category', [MarketCategoryController::class, 'store']); // add market category
    Route::put('category/{id}', [MarketCategoryController::class, 'update']); // edit market category
    Route::post('category/reorder', [MarketCategoryController::class, 'reorder']); // reorder the categories
    Route::post('category/position/{id}', [MarketCategoryController::class, 'updatePosition']); // reorder the categories
    Route::delete('category/{id}', [MarketCategoryController::class, 'destroy']); // delete market category

});
