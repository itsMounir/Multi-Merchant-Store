<?php

use App\Http\Controllers\Api\V1\Users\BillsController;
use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;



Route::prefix('users/bills/')->middleware([
    'auth:sanctum',
    'type.user',
    'isOnline',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::get('bill/{id}', [BillsController::class, 'show']); // get a single bill
    Route::get('new', [BillsController::class, 'newBills']); // get new orders from users
    Route::get('old', [BillsController::class, 'oldBills']); // get accepted or declained bills
    Route::get('search', [BillsController::class, 'search']); // search by store_name
    Route::put('accept/{id}', [BillsController::class, 'acceptBill']); // accept a new bill
    Route::put('cancel/{id}', [BillsController::class, 'cancelBill']); // cancel a new bill
});
