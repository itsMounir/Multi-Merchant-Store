<?php

use App\Http\Controllers\Api\V1\Users\CityController;
use App\Http\Controllers\Api\V1\Users\ProductController;
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

Route::get('noti',function (){

    $noti = new MobileNotificationServices;
    $noti->sendNotification('eYQMl0NXTI--jncxCw6Ncl:APA91bE0BYA1C5ZPNDQmNbYsrsXmiALYhKs1GSUZr6EcgFEuXjfdzY8uainoEeki_-bh5Wz-0Z3y5v2Lyp5FuPFAUqUUcn0fzM-xNbF-JlNVrsLLOEkdC-LHoiTuDh9tjG2vook9pUPd','notificatoin from backend','باك عم الجميع');
});
