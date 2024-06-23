<?php

use App\Http\Controllers\Api\V1\Users\CityController;
use Illuminate\Support\Facades\Route;


Route::prefix('users/cities/')->middleware('auth:sanctum','type.user')->group(function () {

    Route::get('list', [CityController::class, 'index']);
    Route::post('create', [CityController::class, 'create']);
    Route::put('city/{id}', [CityController::class, 'update']);
    Route::post('reorder', [CityController::class, 'reorder']);
    Route::post('position/{id}', [CityController::class, 'updatePosition']); // reorder the categories
    Route::delete('city/{id}', [CityController::class, 'destroy']);
});
