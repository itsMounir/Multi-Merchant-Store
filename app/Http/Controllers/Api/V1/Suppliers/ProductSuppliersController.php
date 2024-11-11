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
use App\Notifications\{
    UpdatePrice

};
use iluminate\pagination\paginator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
class ProductSuppliersController extends Controller
{
    use Responses;

    public function index()
    {
        $supplier=Auth::user();
        $product=$supplier->products()->paginate(10);
        return response()->json([
            'body' => $product
        ]);
    }





    public function store(StoreProductRequest $request){
        DB::beginTransaction();
        try {
            $supplier = Auth::user();
            $hasOffer = filter_var($request->has_offer, FILTER_VALIDATE_BOOLEAN);
            $offerPrice = $hasOffer ? $request->offer_price : 0;
            $maxOfferQuantity = $hasOffer ? $request->max_offer_quantity : 0;
            $offerExpiresAt = $hasOffer ? $request->offer_expires_at : '9999-1-1';
            $productSupplier = $supplier->productSuppliers()->firstOrNew([
                'product_id' => $request->product_id,
            ]);
            $productSupplier->fill([
                'price' => $request->price,
                'is_available'=>1,
                'max_selling_quantity' => $request->max_selling_quantity,
                'quantity' => $productSupplier->quantity + $request->quantity,
            ]);
            if ($hasOffer) {
                $productSupplier->fill([
                    'has_offer' => $hasOffer,
                    'offer_price' => $offerPrice,
                    'max_offer_quantity' => $maxOfferQuantity,
                    'offer_expires_at' => $offerExpiresAt,
                ]);
            }

            $productSupplier->save();


            DB::commit();
            return response()->json(['message' => 'تم اضافة المنتج بنجاح'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => '!حدث خطأ ما'], 500);
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
            'quantity' => 'required_if:is_available,1',
            'price' => 'required_if:is_available,1',
            'max_selling_quantity' => 'required_if:is_available,1',
        ]);
        $product = $supplier->productSuppliers()->find($product_id);

        $updateData = [
            'is_available' => $request->is_available,
            'has_offer' => 0,
            'offer_price' => 0,
            'max_offer_quantity' => 0,
            'offer_expires_at' =>  "9999-1-1",
        ];

        if ($request->is_available == 1) {
            $updateData['quantity'] = $request->quantity;
            $updateData['price'] = $request->price;
            $updateData['max_selling_quantity'] = $request->max_selling_quantity;
        }

        $product->update($updateData);
        $product->save();

        return $this->sudResponse('تم بنجاح');
    }

    /*public function search(Request $request){
        $supplier=Auth::user();
        $product = ProductSupplier::search_Product($supplier->id, $request->search,$request->is_avaliable);
        return $this->indexOrShowResponse('body',$product);

    }*/


    public function get_product_available_or_Not_available(Request $request, $id)
    {
        $supplier = Auth::user();
        $productCount = $supplier->count_product();

        if ($request->has('search') && $request->search != '') {
             $product = ProductSupplier::search_Product($supplier->id, $request->search, $id);


        } else {
            $product = $supplier->products()->where('is_available', $id)->paginate(10);

        }
        return response()->json([

            'data' => $product 
        ]);
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
        $product = DB::table('products')->where('id', $productSupplier->product_id)->first();
        $updateData = $request->only(['price','quantity','offer_price', 'max_offer_quantity', 'offer_expires_at']);
       if($request->price){
        $markets=$supplier->getMarketsToNotify();
        foreach ($markets as $market) {
            Notification::send($market, new UpdatePrice($product,$supplier));
        }

       }
       if (isset($updateData['quantity'])) {
        if ($updateData['quantity'] == 0) {
            $productSupplier->is_available = 0;
            $productSupplier->has_offer = 0;
            $productSupplier->max_offer_quantity = 0;
            $productSupplier->offer_price = 0;
            $productSupplier->offer_expires_at = "9999-1-1";
        }
    }
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
