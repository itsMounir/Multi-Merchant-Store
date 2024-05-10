<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Suppliers\ProductSuppliersController;
use App\Http\Controllers\Api\V1\Suppliers\SuppliersController;
use App\Http\Controllers\Api\V1\Suppliers\BillController;
use App\Models\Image;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/image', function (Request $request) {
//     // Validate the incoming request
//     $request->validate([
//         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation rules
//     ]);

//     // Store the image
//     $path = $request->file('image')->store('products', 'public');

//     // Save the image path to your database
//     // Assuming you have an Image model and images table
//     $image = new Image();
//     $image->url = $path;
//     $image->imageable_type = 'App\Models\Product';
//     $image->imageable_id = 1;
//     $image->save();

//     return response()->json(['message' => 'Image uploaded successfully', 'id' => $image->id, 'path' => $path]);
// });

// // Route to serve image via API
// Route::get('/image/{id}', function ($id) {
//     // Fetch the image path from the database
//     $image = Image::findOrFail($id);

//     // Serve the image
//     return response()->file(storage_path('app/public/' . $image->url));
// });

//  Admin Section
include __DIR__ . '/Developers/Users/Auth.php';
include __DIR__ . '/Developers/Users/Market.php';
include __DIR__ . '/Developers/Users/Supplier.php';
include __DIR__ . '/Developers/Users/Bills.php';
include __DIR__ . '/Developers/Users/Offers.php';
include __DIR__ . '/Developers/Users/Products.php';
include __DIR__ . '/Developers/Users/Cities.php';
include __DIR__ . '/Developers/Users/Statistics.php';

// Supplier Section
include __DIR__ . '/Developers/Suppliers/Auth.php';
include __DIR__ . '/Developers/Suppliers/Suppliers.php';

// Market Section
include __DIR__ . '/Developers/Markets/Auth.php';
include __DIR__ . '/Developers/Markets/Markets.php';
