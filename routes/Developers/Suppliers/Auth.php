<?php
use Illuminate\Support\Facades\Route;
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
use App\Enums\TokenAbility;

Route::prefix('suppliers/')->group(function () {
    // auth routes
    Route::post('/register', [RegisterController::class, 'create']);
    Route::post('/login', [LoginController::class, 'create']);
    Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth:sanctum', 'type.supplier']);

    Route::get('refresh-token', [LoginController::class, 'refreshToken'])
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
    ]);

});


// supplier section
