<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Exceptions\InActiveAccountException;
use App\Filters\Markets\{
    ProductsFilters,
    SuppliersFilters
};
use App\Http\Controllers\Controller;


use App\Models\{
    Offer,
    ProductCategory,
    Supplier
};

use Illuminate\Http\JsonResponse;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SuppliersFilters $suppliersFilters): JsonResponse
    {
        $offers = Offer::latest()->get();
        $suppliers = $suppliersFilters->applyFilters(Supplier::query())->active()->site()->orderBy('min_bill_price')->get();
        return response()->json([
            'offers' => $offers,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier, ProductsFilters $productsFilters): JsonResponse
    {
        throw_if($supplier->status != 'نشط', new InActiveAccountException($supplier->store_name));


        $products = $productsFilters->applyFilters($supplier->availableProducts()->getQuery())->get();
        $categories = ProductCategory::get(['id', 'name']);

        return response()->json([
            'supplier' => $supplier,
            'product_categories' => $categories,
            'products' => $products,
        ]);
    }
}
