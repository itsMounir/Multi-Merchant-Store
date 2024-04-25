<?php

use App\Http\Controllers\Api\V1\Users\Auth\{
    CreateAccountController,
    LoginController,
};
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::prefix('users/auth/')->group(function () {

    Route::post('account/create', [CreateAccountController::class, 'create']);
    Route::post('login', [LoginController::class, 'create']);
    Route::post('logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.user']);
});

Route::middleware(['auth:sanctum', 'ownerMiddleware'])->get('xx', function () {
    $id = auth()->user()->id;
    $user = User::find($id);
    $user->assignRole('owner');
    $permission = $user->getRoleNames();
    return $permission;
});
