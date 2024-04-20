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
    UpdatePriceRequest,
    AddOfferRequest
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


    public function store(StoreProductRequest $request){

        DB::beginTransaction();
        try {
            $supplier = Auth::user();
            $hasOffer = filter_var($request->has_offer, FILTER_VALIDATE_BOOLEAN);

            $productData = [
                'price' => $request->price,
                'max_selling_quantity' => $request->max_selling_quantity,
                'has_offer' => $hasOffer,
                'offer_price' => $hasOffer ? $request->offer_price : null,
                'max_offer_quantity' => $hasOffer ? $request->max_offer_quantity : null,
                'offer_expires_at' => $hasOffer ? $request->offer_expires_at : null,
            ];

            $supplier->productSuppliers()->updateOrCreate([
                'supplier_id' => $supplier->id,
                'product_id' => $request->product_id,
            ], $productData);

            DB::commit();
            return $this->sudResponse('Product has been added.');
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sudResponse('An error occurred while adding the product.');
        }
}



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



    public function is_available(Request $request, $product_id)
    {
        $supplier = Auth::user();
        $product = ProductSupplier::where('id', $product_id)->first();

            $product->update([
                'is_available' => $request->is_available,
            ]);

            return $this->sudResponse('Done');

    }


    public function get_product_available_or_Not_available($id){
        $supplier=Auth::user();
        $product=$supplier->productSuppliers()
        ->where('is_available',$id)
        ->get();
        return $this->indexOrShowResponse('message',$product);
    }

    public function offer(AddOfferRequest $request ,$product_id){
        $supplier=Auth::user();
        $product=$supplier->products()
        ->where('product_id',$product_id)
        ->first();
        $product->update([
            'has_offer'=>$request->has_offer,
            'offer_price'=>$request->offer_price,
            'max_offer_quantity'=>$request->max_offer_quantity,
            'offer_expires_at'=>$request->offer_expires_at
        ]);
        return $this->sudResponse('Done');

    }
}
