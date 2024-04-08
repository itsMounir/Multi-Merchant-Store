<?php

use App\Http\Controllers\Api\V1\Auth\{ VerificationController};
Route::post('/check',[VerificationController::class,'check']);

