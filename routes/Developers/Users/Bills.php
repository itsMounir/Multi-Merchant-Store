<?php

use App\Http\Controllers\Api\V1\Users\BillsController;
use Illuminate\Support\Facades\Route;



Route::prefix('users/bills/')->group(function () {

    Route::get('bill/{id}', [BillsController::class, 'show']); // get a single bill
    Route::get('order', [BillsController::class, 'newBills']); // get new orders from users
    Route::get('bills', [BillsController::class, 'oldBills']); // get accepted or declained bills 
    Route::put('accept/{id}', [BillsController::class, 'acceptBill']); // accept a new bill   
    Route::put('cancel/{id}', [BillsController::class, 'cancelBill']); // cancel a new bill

});
