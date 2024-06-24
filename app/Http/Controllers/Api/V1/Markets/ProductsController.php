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
            ->join('suppliers', 'suppliers.id', '=', 'product_supplier.supplier_id')
            ->orderBy('product_supplier.price')
            ->select(
                'products.id',
                'products.product_category_id',
                'products.name',
                'products.discription',
                'suppliers.id as supplier_id',
                'suppliers.store_name',
                'suppliers.min_bill_price',
                'suppliers.min_selling_quantity',
                'suppliers.delivery_duration',
                'product_supplier.price',
                'product_supplier.max_selling_quantity',
                'product_supplier.has_offer',
                'product_supplier.offer_price',
                'product_supplier.max_offer_quantity'
            )
            ->distinct() // Ensure unique results
            ->paginate(10)
            ->toArray();
        // // Filter products where suppliers array is empty
        // $filteredProducts = array_filter($products['data'], function ($product) {
        //     return empty($product['suppliers']) == false;
        // });

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
