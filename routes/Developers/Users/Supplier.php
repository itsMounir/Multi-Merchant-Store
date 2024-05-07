<?php

use App\Http\Controllers\api\v1\users\SupplierCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Users\SupplierUserController;


Route::prefix('users/supplier')->middleware('auth:sanctum')->group(function () {

    Route::get('get', [SupplierUserController::class, 'index']); // get filtered supplier users {activated- deactivated - baned}
    Route::post('activate/{id}', [SupplierUserController::class, 'activateSupplierUser']); // activate supplier user 
    Route::post('ban/{id}', [SupplierUserController::class, 'banSupplierUser']); // ban supplier user
    Route::get('profile/{id}', [SupplierUserController::class, 'profile']); // get user profile
    Route::put('profile/edit/{id}', [SupplierUserController::class, 'profileEdit']); // Edit user profile
    Route::get('with-bills/{id}', [SupplierUserController::class, 'userWithBills']); // get user with his bills

    Route::get('category', [SupplierCategoryController::class, 'index']); // get market categories
    Route::post('category', [SupplierCategoryController::class, 'store']); // add market category
    Route::put('category/{id}', [SupplierCategoryController::class, 'update']); // edit market category
    Route::delete('category/{id}', [SupplierCategoryController::class, 'destroy']); // delete market category

});
