<?php

use App\Http\Controllers\api\v1\users\CityController;
use Illuminate\Support\Facades\Route;


Route::prefix('users/cities/')->middleware('auth:sanctum')->group(function () {

    Route::get('list', [CityController::class, 'index']);
    Route::post('create', [CityController::class, 'create']);
    Route::put('city/{id}', [CityController::class, 'update']);
    Route::delete('city/{id}', [CityController::class, 'destroy']);
});
