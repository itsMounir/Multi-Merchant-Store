<?php

use App\Http\Controllers\api\v1\users\CityController;
use Illuminate\Support\Facades\Route;


Route::prefix('users/cities/')->group(function () {

    Route::get('city', [CityController::class, 'index']);
    Route::post('city', [CityController::class, 'create']);
    Route::put('city/{id}', [CityController::class, 'update']);
    Route::delete('city/{id}', [CityController::class, 'destroy']);
});
