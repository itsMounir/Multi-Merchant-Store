<?php

use App\Http\Controllers\Api\V1\Users\CityController;
use App\Http\Controllers\Api\V1\Users\ProductController;
use App\Http\Controllers\Api\V1\Users\UsersController;
use Illuminate\Support\Facades\Route;
use App\Services\MobileNotificationServices;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return "welcom";
});

Route::get('noti', [App\Http\Controllers\Api\V1\Users\NotificationController::class, 'sendNotificationToDevice']);
