<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Users\ProductController;

Route::prefix('users/products/')->group(function () {

    Route::get('list', [ProductController::class, 'index']);  // get products by type
    Route::get('info/{id}', [ProductController::class, 'show']);  // get product by id
    Route::post('new', [ProductController::class, 'store']);  // create product
    Route::put('update/{id}', [ProductController::class, 'update']);  // update product
    Route::delete('delete/{id}', [ProductController::class, 'destroy']);  // delete product
    Route::put('restore/{id}', [ProductController::class, 'restore']); // restory deleted product

    Route::get('category', [ProductController::class, 'getCategories']);  // get categories
    Route::post('category', [ProductController::class, 'createCategory']);  // create category
    Route::put('category/{id}', [ProductController::class, 'updateCategory']);  // update category
    Route::delete('category/{id}', [ProductController::class, 'destroyCategory']);  // delete category

});
