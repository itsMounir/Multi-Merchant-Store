<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\ProductSupplier;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Responses;
use App\Http\Requests\Api\V1\Suppliers\{
    StoreProductRequest,
    UpdatePriceRequest
};
use Illuminate\Support\Facades\DB;
class ProductSuppliersController extends Controller
{
    use Responses;

    public function index()
    {
        $supplier=Auth::user();
        if(!$supplier){
            return $this->sudResponse('Unauthorized',401);
        }
        $product=$supplier->products;
        return $this->indexOrShowResponse('message',$product);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(StoreProductRequest $request)
    {
        $supplier = Auth::user();
        if (!$supplier) {
            return $this->sudResponse('Unauthorized', 401);
        }
        DB::beginTransaction();
        try {
            foreach ($request['products'] as $product) {
                $supplier->products()->syncWithoutDetaching([
                    $product['id'] => ['price' => $product['price'],'min_selling_quantity'=>$product['min_selling_quantity']]
                ]);
            }
            DB::commit();
            return $this->sudResponse('Products have been added successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sudResponse('An error occurred', 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
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
    public function update(UpdatePriceRequest $request, $product_id)
    {
        $supplier = Auth::user();


        if (!$supplier) {
            return $this->sudResponse('Unauthorized', 401);
        }
        $productSupplier = $this->findProductSupplier($supplier->id, $product_id);
        if (!$productSupplier) {
            return $this->sudResponse('Not found', 404);
        }
        $productSupplier->update(['price' => $request->price]);
        return $this->sudResponse('price has been updated');
    }





    private function findProductSupplier($supplierId, $productId)
    {

        return ProductSupplier::where('supplier_id', $supplierId)
                              ->where('product_id', $productId)
                              ->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        $supplier=Auth::user();
        if(!$supplier){
            return $this->sudResponse('Unauthorized',401);
        }
        $product=$supplier->products()->find($product_id);
        if(!$product){
            return $this->sudResponse('Not found',404);
        }
        $supplier->products()->detach($product_id);
        return $this->sudResponse('The product has been deleted');

    }
}
