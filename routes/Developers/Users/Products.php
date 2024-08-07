<?php

use App\Http\Controllers\Api\V1\Users\ProductCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Users\ProductController;
use App\Enums\TokenAbility;

Route::prefix('users/products/')->middleware([
    'auth:sanctum',
    'type.user',
    'isOnline',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::get('list', [ProductController::class, 'index']);  // get products by type
    Route::get('search', [ProductController::class, 'filterAndSearch']); // filter by category and search product by name
    Route::get('trash', [ProductController::class, 'trash']); // get deleted products
    Route::get('info/{id}', [ProductController::class, 'show']);  // get product by id
    Route::post('new', [ProductController::class, 'store']);  // create product
    Route::post('update/{id}', [ProductController::class, 'update']);  // update product
    Route::delete('delete/{id}', [ProductController::class, 'destroy']);  // delete product
    Route::put('restore/{id}', [ProductController::class, 'restore']); // restory deleted product

    Route::get('category', [ProductCategoryController::class, 'index']);  // get categories
    Route::get('category/{id}', [ProductCategoryController::class, 'show']); // get category by ID
    Route::post('category', [ProductCategoryController::class, 'store']);  // create category
    Route::put('category/{id}', [ProductCategoryController::class, 'update']);  // update category
    Route::post('category/reorder', [ProductCategoryController::class, 'reorder']); // reorder the categories
    Route::post('category/position/{id}', [ProductCategoryController::class, 'updatePosition']); // reorder the categories
    Route::delete('category/{id}', [ProductCategoryController::class, 'destroy']);  // delete category

});
