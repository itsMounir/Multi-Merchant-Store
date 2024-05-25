<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Filters\Markets\ProductsFilters;
use App\Http\Controllers\Controller;
use App\Models\{
    Product,
    Supplier
};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsFilters $productsFilters): JsonResponse
    {
        $products = $productsFilters->applyFilters(Product::query())
            ->join('product_supplier', 'products.id', '=', 'product_supplier.product_id')
            ->orderBy('product_supplier.price')
            ->paginate(10, ['product_id', 'product_category_id', 'discription', 'name', 'size', 'size_of', 'supplier_id', 'price', 'max_selling_quantity'])
            ->toArray();

        return response()->json([
            'products' => $products
        ]);
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
    public function show(Product $product): JsonResponse
    {
        return $this->indexOrShowResponse('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
