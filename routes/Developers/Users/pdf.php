<?php

use App\Http\Controllers\Api\V1\Users\PdfController;
use Illuminate\Support\Facades\Route;
use App\Enums\TokenAbility;


Route::prefix('users/pdf')->middleware([
    'auth:sanctum',
    'type.user',
    'isOnline',
    'ability:' . TokenAbility::ACCESS_API->value
])->group(function () {

    Route::get('generate-pdf/{id}',[PdfController::class,'generate_pdf']);
});
