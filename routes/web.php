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
    $noti->sendNotification('dF4ervL2QYe7-AfgOD1KA2:APA91bEeeatGUCUyUlra4IU2P25qQLdXJrbIpTVDIsJeMYf_NDJsNemjqEd3VB1bMjKl-GSMstvLU_j96LgkrYSEhAc3dOpdK-NZuCzMKbdH-UBECzd4wHQXuUVpIf5mjDPSAB1bBOcA','notificatoin from backend','باك عم الجميع');
});
