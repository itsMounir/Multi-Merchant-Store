<?php

use App\Http\Controllers\Api\V1\Users\Auth\{
    LoginController,
    RegisterController,
};
use App\Http\Controllers\Api\V1\Users\MarketUserController;
use App\Http\Controllers\Api\V1\Users\SupplierUserController;
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
        Route::get('get ', [MarketUserController::class, 'marketUsers']); // get filtered market users {activated- deactivated - baned}
        Route::post('{id}/activate', [MarketUserController::class, 'activateMarketUser']); // activate market user
        Route::post('{id}/deactivate', [MarketUserController::class, 'deactivateMarketUser']); // deactivate market user
        Route::post('{id}/ban', [MarketUserController::class, 'banMarketUser']); // ban market user
    });

    /**
     *  FOR DEALING WITH SUPPLIER USERS
     */
    Route::prefix('supplier')->group(function () {
        Route::get('get', [SupplierUserController::class, 'supplierUsers']); // get filtered supplier users {activated- deactivated - baned}
        Route::post('{id}/activate', [SupplierUserController::class, 'activateSupplierUser']); // activate supplier user 
        Route::post('{id}/deactivate', [SupplierUserController::class, 'deactivateSupplierUser']); // deactivate supplier user
        Route::post('{id}/ban', [SupplierUserController::class, 'banSupplierUser']); // ban supplier user
    });
});
