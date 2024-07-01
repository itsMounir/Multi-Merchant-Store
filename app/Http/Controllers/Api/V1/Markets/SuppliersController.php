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

        $suppliers =
            $suppliersFilters->applyFilters(Supplier::query())
                ->withCount('bills')
                ->active()
                ->site()
                ->orderBy('bills_count', 'desc')
                //->orderBy('min_bill_price')
                ->get()->append(['min_bill_price', 'image']);

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
        $supplier->append(['min_bill_price', 'image']);

        $products = $productsFilters->applyFilters($supplier->availableProducts()->getQuery())->get();
        $categories = ProductCategory::get(['id', 'name']);
        $offers = Offer::where('supplier_id', $supplier->id)->get();
        $products_with_offer = [];
        $products_without_offer = [];
        foreach ($products as $product) {
            if ($product->has_offer) {
                $products_with_offer[] = $product;
            } else {
                $products_without_offer[] = $product;
            }
        }

        return response()->json([
            'supplier' => $supplier,
            'product_categories' => $categories,
            'slider_offers' => $offers,
            'products_with_offer' => $products_with_offer,
            'products_without_offer' => $products_without_offer,
        ]);
    }
}
