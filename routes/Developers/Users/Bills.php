<?php

use App\Http\Controllers\Api\V1\Users\BillsController;
use Illuminate\Support\Facades\Route;



Route::prefix('users/bills/')->group(function () {

    Route::get('order', [BillsController::class, 'newBills']);  // get new orders from users
    Route::get('bills', [BillsController::class, 'oldBills']); // get accepted or declained bills 
    Route::post('{id}/decide', [BillsController::class, 'billDecision']); // accept or declain order  

});
