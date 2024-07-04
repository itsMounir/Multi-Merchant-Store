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

        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return $this->indexOrShowResponse('product', $product);
    }
}
