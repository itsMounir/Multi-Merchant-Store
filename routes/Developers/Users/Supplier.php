<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Users\SupplierUserController;


Route::prefix('users/supplier')->group(function () {

    Route::get('get', [SupplierUserController::class, 'supplierUsers']); // get filtered supplier users {activated- deactivated - baned}
    Route::post('activate/{id}', [SupplierUserController::class, 'activateSupplierUser']); // activate supplier user 
    Route::post('ban/{id}', [SupplierUserController::class, 'banSupplierUser']); // ban supplier user
    Route::get('profile/{id}', [SupplierUserController::class, 'profile']); // get user profile
    Route::post('profile/edit/{id}', [SupplierUserController::class, 'profileEdit']); // Edit user profile
    Route::get('with-bills/{id}', [SupplierUserController::class, 'userWithBills']); // get user with his bills

    Route::get('category', [SupplierUserController::class, 'getCategories']); // get market categories
    Route::post('category', [SupplierUserController::class, 'createCategory']); // add market category
    Route::put('category/{id}', [SupplierUserController::class, 'updateCategory']); // edit market category
    Route::delete('category/{id}', [SupplierUserController::class, 'destroyCategory']); // delete market category

});
