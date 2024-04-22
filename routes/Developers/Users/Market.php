<?php

use App\Http\Controllers\Api\V1\Users\MarketUserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users/market/')->group(function () {

    Route::get('get', [MarketUserController::class, 'marketUsers']); // get filtered market users {activated- deactivated - baned}
    Route::post('activate/{id}', [MarketUserController::class, 'activateMarketUser']); // activate market user
    Route::post('ban/{id}', [MarketUserController::class, 'banMarketUser']); // ban market user
    Route::get('profile/{id}', [MarketUserController::class, 'profile']); // get user profile
    Route::post('profile/edit/{id}', [MarketUserController::class, 'profileEdit']); // Edit user profile
    Route::get('with-bills/{id}', [MarketUserController::class, 'userWithBills']); // get user with his bills

    Route::get('category', [MarketUserController::class, 'getCategories']); // get market categories
    Route::post('category', [MarketUserController::class, 'createCategory']); // add market category
    Route::put('category{id}', [MarketUserController::class, 'updateCategory']); // edit market category
    Route::delete('category/{id}', [MarketUserController::class, 'destroyCategory']); // delete market category

});
