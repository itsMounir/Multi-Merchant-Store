<?php

use App\Http\Controllers\Api\V1\Users\Auth\{
    EmployeeController,
    AuthController,
};
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::prefix('users/auth/')->group(function () {

    Route::post('login', [AuthController::class, 'create']);
    Route::post('logout', [AuthController::class, 'destroy'])->middleware(['auth:sanctum', 'type.user']);
    Route::get('profile', [AuthController::class, 'profile'])->middleware(['auth:sanctum', 'type.user']);
});

Route::prefix('users/employee')->middleware('auth:sanctum')->group(function () {

    Route::post('create', [EmployeeController::class, 'create']);
    Route::get('list', [EmployeeController::class, 'index']);
    Route::get('info/{id}', [EmployeeController::class, 'show']);
    Route::put('update/{id}', [EmployeeController::class, 'update']);
    Route::delete('delete/{id}', [EmployeeController::class, 'destroy']);
    Route::post('change-password/{id}', [EmployeeController::class, 'changePassword']);
});
