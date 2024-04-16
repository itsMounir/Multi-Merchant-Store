<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Filters\Markets\ProductsFilters;
use App\Filters\Markets\SuppliersFilters;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SuppliersFilters $suppliersFilters)
    {
        $suppliers = $suppliersFilters->applyFilters(Supplier::query())->site()->get();
        return $this->indexOrShowResponse('suppliers',$suppliers);
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
    public function show(Supplier $supplier,ProductsFilters $productsFilters)
    {
        // dd($supplier->products()->getQuery());
        $products = $productsFilters->applyFilters($supplier->products()->getQuery())->get();
        return response()->json([
            'supplier' => $supplier->with('goals')->get(),
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
