<?php

use App\Http\Controllers\Api\V1\Users\SupplierCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Users\SupplierUserController;


Route::prefix('users/supplier')->middleware('auth:sanctum')->group(function () {

    Route::get('get', [SupplierUserController::class, 'index']); // get filtered supplier users {activated- deactivated - baned}
    Route::get('search', [SupplierUserController::class, 'search']); // search by Store_name
    Route::post('activate/{id}', [SupplierUserController::class, 'activate']); // activate supplier user
    Route::post('ban/{id}', [SupplierUserController::class, 'ban']); // ban supplier user
    Route::get('profile/{id}', [SupplierUserController::class, 'show']); // get user profile
    Route::put('profile/edit/{id}', [SupplierUserController::class, 'update']); // Edit user profile
    Route::get('with-bills/{id}', [SupplierUserController::class, 'userWithBills']); // get user with his bills
    Route::get('with-products/{id}', [SupplierUserController::class, 'userWithProducts']); // get user with his products

    Route::get('category', [SupplierCategoryController::class, 'index']); // get supplier categories
    Route::post('category', [SupplierCategoryController::class, 'store']); // add supplier category
    Route::put('category/{id}', [SupplierCategoryController::class, 'update']); // edit supplier category
    Route::post('category/reorder', [SupplierCategoryController::class, 'reorder']); // reorder the categories
    Route::post('category/position/{id}', [SupplierCategoryController::class, 'updatePosition']); // reorder the categories
    Route::delete('category/{id}', [SupplierCategoryController::class, 'destroy']); // delete supplier category

});
