<?php

use App\Http\Controllers\Api\V1\Suppliers\Auth\{
    LoginController,
    RegisterController,
    VerificationController
};
use App\Http\Controllers\Api\V1\Suppliers\{
    ProductSuppliersController,
    SupplierContoller,
    BillController,
    ReportController
};

Route::prefix('suppliers/')->group(function () {
    // auth routes
    Route::post('/register', [RegisterController::class, 'create']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.supplier']);
});


// supplier section
