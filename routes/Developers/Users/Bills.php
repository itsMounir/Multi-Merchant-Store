<?php

use App\Http\Controllers\Api\V1\Users\BillsController;
use Illuminate\Support\Facades\Route;



Route::prefix('users/bills/')->middleware('auth:sanctum')->group(function () {

    Route::get('bill/{id}', [BillsController::class, 'show']); // get a single bill
    Route::get('new', [BillsController::class, 'newBills']); // get new orders from users
    Route::get('old', [BillsController::class, 'oldBills']); // get accepted or declained bills
    Route::put('accept/{id}', [BillsController::class, 'acceptBill']); // accept a new bill
    Route::put('cancel/{id}', [BillsController::class, 'cancelBill']); // cancel a new bill
});
