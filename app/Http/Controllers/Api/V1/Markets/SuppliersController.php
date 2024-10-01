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
    Supplier,
    Product
};

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
                // ->site()
                ->join('distribution_locations', function ($join) {
                    $join->on('suppliers.id', '=', 'distribution_locations.supplier_id')
                        ->where('distribution_locations.to_city_id', '=', Auth::user()->city_id);
                })
                ->orderByDesc('bills_count')
                ->orderBy('distribution_locations.min_bill_price')
                ->get()
                ->append(['min_bill_price', 'image']);

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


        $products = Product::query()->join('product_supplier', 'products.id', '=', 'product_supplier.product_id')
            ->where('product_supplier.supplier_id', $supplier->id)
            ->where('product_supplier.is_available', true)
            ->select([
                'products.*',
                'product_supplier.price',
                //'product_supplier.is_available',
                'product_supplier.max_selling_quantity',
                'product_supplier.has_offer',
                'product_supplier.offer_price',
                'product_supplier.max_offer_quantity',
                'product_supplier.offer_expires_at'
            ])
            ->get();

        $filtered_products = $productsFilters->applyFilters(
            Product::query()->join('product_supplier', 'products.id', '=', 'product_supplier.product_id')
                ->where('product_supplier.supplier_id', $supplier->id)
                ->where('product_supplier.is_available', true)
        )
            ->select([
                'products.*',
                'product_supplier.price',
                //'product_supplier.is_available',
                'product_supplier.max_selling_quantity',
                'product_supplier.has_offer',
                'product_supplier.offer_price',
                'product_supplier.max_offer_quantity',
                'product_supplier.offer_expires_at'
            ])
            ->get();

        $categories_ids = [];
        foreach ($products as $product) {
            $categoryId = $product->product_category_id;

            if (!in_array($categoryId, $categories_ids)) {
                $categories_ids[] = $categoryId;
            }
        }
        $categories = ProductCategory::whereIn('id', $categories_ids)->orderBy('position')->get(['id', 'name']);
        $offers = Offer::where('supplier_id', $supplier->id)->get();
        $products_with_offer = [];
        $products_without_offer = [];
        foreach ($filtered_products as $product) {
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
