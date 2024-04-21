<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Exceptions\InActiveAccountException;
use App\Filters\Markets\ProductsFilters;
use App\Filters\Markets\SuppliersFilters;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuppliersController extends Controller
{
    /**
     * get suppliers categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = SupplierCategory::get(['id', 'type']);
        return $this->indexOrShowResponse('supplier_categories', $categories);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(SuppliersFilters $suppliersFilters): JsonResponse
    {
        $suppliers = $suppliersFilters->applyFilters(Supplier::query())->active()->site()->get();
        return $this->indexOrShowResponse('suppliers', $suppliers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier, ProductsFilters $productsFilters): JsonResponse
    {
        // dd($supplier->products()->getQuery());
        throw_if($supplier->status != 'نشط', new InActiveAccountException($supplier->store_name));
        $products = $productsFilters->applyFilters($supplier->products()->getQuery())->get();
        return response()->json([
            'supplier' => $supplier->with('goals')->first(),
            'products' => $products,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
