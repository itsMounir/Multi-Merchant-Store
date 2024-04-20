<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use App\Models\SupplierCategory;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = SupplierCategory::get(['id','type']);
        return $this->indexOrShowResponse('supplier_categories',$categories);
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
    public function show(SupplierCategory $supplierCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierCategory $supplierCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierCategory $supplierCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierCategory $supplierCategory)
    {
        //
    }
}
