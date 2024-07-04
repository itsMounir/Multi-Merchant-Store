<?php

use App\Http\Controllers\Api\V1\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//Route::get('noti',[UsersController::class , 'notification']);

//  Admin Section
include __DIR__ . '/Developers/Users/Auth.php';
include __DIR__ . '/Developers/Users/Market.php';
include __DIR__ . '/Developers/Users/Supplier.php';
include __DIR__ . '/Developers/Users/Bills.php';
include __DIR__ . '/Developers/Users/Offers.php';
include __DIR__ . '/Developers/Users/Products.php';
include __DIR__ . '/Developers/Users/Cities.php';
include __DIR__ . '/Developers/Users/Statistics.php';
include __DIR__ . '/Developers/Users/notification.php';

// Supplier Section
include __DIR__ . '/Developers/Suppliers/Auth.php';
include __DIR__ . '/Developers/Suppliers/Suppliers.php';

// Market Section
include __DIR__ . '/Developers/Markets/Auth.php';
include __DIR__ . '/Developers/Markets/Markets.php';
