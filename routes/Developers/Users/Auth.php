<?php

use App\Http\Controllers\Api\V1\Users\Auth\{
    LoginController,
    RegisterController,
};
use App\Http\Controllers\Api\V1\Users\MarketUserController;
use App\Http\Controllers\Api\V1\Users\SupplierUserController;
use App\Models\Market;
use Illuminate\Support\Facades\Route;


Route::prefix('users/')->group(function () {
    /**
     * FOR AUTHENTICATION 
     */
    Route::post('/register', [RegisterController::class, 'create']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.user']);

    /**
     * FOR DEALING WITH MARKET USERS
     */
    Route::prefix('market')->group(function () {
        Route::get('get', [MarketUserController::class, 'marketUsers']); // get filtered market users {activated- deactivated - baned}
        Route::post('activate/{id}', [MarketUserController::class, 'activateMarketUser']); // activate market user
        Route::post('ban/{id}', [MarketUserController::class, 'banMarketUser']); // ban market user
        Route::get('profile/{id}', [MarketUserController::class, 'profile']); // get user profile
        Route::post('profile/edit/{id}', [MarketUserController::class, 'profileEdit']); // Edit user profile
        Route::get('with-bills/{id}', [MarketUserController::class, 'userWithBills']); // get user with his bills
    });

    /**
     *  FOR DEALING WITH SUPPLIER USERS
     */
    Route::prefix('supplier')->group(function () {
        Route::get('get', [SupplierUserController::class, 'supplierUsers']); // get filtered supplier users {activated- deactivated - baned}
        Route::post('activate/{id}', [SupplierUserController::class, 'activateSupplierUser']); // activate supplier user 
        Route::post('ban/{id}', [SupplierUserController::class, 'banSupplierUser']); // ban supplier user
        Route::get('profile/{id}', [SupplierUserController::class, 'profile']); // get user profile
        Route::post('profile/edit/{id}', [SupplierUserController::class, 'profileEdit']); // Edit user profile
        Route::get('with-bills/{id}', [SupplierUserController::class, 'userWithBills']); // get user with his bills

    });
});
