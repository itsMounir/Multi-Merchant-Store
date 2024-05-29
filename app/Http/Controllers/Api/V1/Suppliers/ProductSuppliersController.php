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
    AddOfferRequest,
    UpdateOfferRequest
};
use Illuminate\Support\Facades\DB;
class ProductSuppliersController extends Controller
{
    use Responses;

    public function index()
    {
        $supplier=Auth::user();
        $product=$supplier->products()->get();
        return $this->indexOrShowResponse('body',$product);
    }


        public function search(Request $request){
        $supplier=Auth::user();
        $product = ProductSupplier::search_Product($supplier->id, $request->search,$request->is_avaliable);
        return $this->indexOrShowResponse('body',$product);

    }




    public function store(StoreProductRequest $request){

        DB::beginTransaction();
        try {
            $supplier = Auth::user();
            $hasOffer = filter_var($request->has_offer, FILTER_VALIDATE_BOOLEAN);

            $productData = [
                'price' => $request->price,
                'product_id' => $request->product_id,
                'max_selling_quantity' => $request->max_selling_quantity,
                'has_offer' => $hasOffer,

                'offer_price' => $hasOffer ? $request->offer_price : 0,
                'max_offer_quantity' => $hasOffer ? $request->max_offer_quantity : 0,
                'offer_expires_at' => $hasOffer ? $request->offer_expires_at : '9999-1-1',
            ];

            $supplier->productSuppliers()->updateOrCreate([
                'supplier_id' => $supplier->id,
                'product_id' => $request->product_id,
            ], $productData);

            DB::commit();
            return $this->sudResponse('تم اضافة المنتج بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sudResponse('!حدث خطأ ما ');
        }
}



   /* public function update(UpdatePriceRequest $request, $product_id)
    {
        $supplier = Auth::user();
        $productSupplier = $this->findProductSupplier($supplier->id, $product_id);
        $productSupplier->update(['price' => $request->price]);
        return $this->sudResponse('تم تعديل السعر');
    }





    private function findProductSupplier($supplierId, $productId)
    {

        return ProductSupplier::where('supplier_id', $supplierId)
                              ->where('id', $productId)
                              ->first();
    }*/

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        $supplier=Auth::user();
       $product= $supplier->productSuppliers()->find($product_id)->first();
        $product->delete();
        return $this->sudResponse('تم حذف المنتج بنجاح');

    }



    public function is_available(Request $request, $product_id)
    {
        $supplier = Auth::user();

        $request->validate([
            'is_available' => 'required',
        ]);
        $product = $supplier->productSuppliers()->find($product_id);

        //return $product;
            $product->update([
                'is_available' => $request->is_available,
            ]);
            $product->save();

            return $this->sudResponse('تم بنجاح');

    }


    public function get_product_available_or_Not_available($id){
        $supplier=Auth::user();
        $product=$supplier->products()
        ->where('is_available',$id)
        ->get();
        return $this->indexOrShowResponse('body',$product);
    }



    public function offer(AddOfferRequest $request, $product_id)
    {
        $supplier = Auth::user();
        $productPivot = $supplier->productSuppliers()->find($product_id);
        $productPivot->update( $request->all());
        $productPivot->save();
        return $this->sudResponse('تم اضافة عرض لهذا المنتج ');
    }




    public function update(UpdateOfferRequest $request, $product_id)
    {
        $supplier = Auth::user();
        $productSupplier = $this->findProductSupplier($supplier->id, $product_id);

        $updateData = $request->only(['price', 'offer_price', 'max_offer_quantity', 'offer_expires_at']);
        $productSupplier->update($updateData);


        return $this->sudResponse('تم تعديل المنتج بنجاح');
    }

    private function findProductSupplier($supplierId, $productId)
    {
        return ProductSupplier::where('supplier_id', $supplierId)
                              ->where('id', $productId)
                              ->first();
    }




}
