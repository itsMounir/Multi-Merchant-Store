<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Supplier;
use App\Traits\Responses;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Models\Market;
use App\Services\BillsServices;
class BillController extends Controller
{
    use Responses;
    public function index()
    {
        $supplier = Auth::user();
        if (!$supplier) {
            return $this->sudResponse('unauthorized',401);
        }
        $bills = $supplier->bills()->with(['market', 'products'])->get();
        return $this->indexOrShowResponse('message',$bills);
    }




    public function update(Request $request, Bill $bill)
{
    return DB::transaction(function () use ($request, $bill) {
        $billService = new BillsServices;
        $supplier = Auth::user();
        $market = Market::find($request->market_id);

        $total_price = $billService->calculatePrice($bill, $market);

        $total_price -= $billService->discounts($market, $total_price);

        $bill->update([
            'total_price' => $total_price,
            'payment_method_id' => $request->payment_method_id ?? $bill->payment_method_id,
            'market_id' => $market->id,
            'supplier_id' => $supplier->id,
            'discount_code' => $request->discount_code ?? $bill->discount_code,
        ]);

        foreach ($request->cart as $item) {
            $bill->products()->syncWithoutDetaching([
                $item['id'] => [
                    'quantity' => $item['quantity'],
                    'updated_at' => now(),
                ],
            ]);
        }

        $bill->save();

        return $this->sudResponse('Bill Updated Successfully');
    });
}




    /**
     * Remove the specified resource from storage.
     */
   /* public function destroy(Request $request)
    {

        $supplier = Auth::user();
        $bill = $supplier->bills()->where('id', $request->id)->where('status', '=', 'انتظار')->first();
        if (!$bill) {
            return $this->sudResponse('Not found', 404);
        }
        $bill->rejection_reason=$request->reason;
        $bill->save();
        $bill->delete();
        return $this->sudResponse('Bill has been deleted');
    }*/


    public function reject(Request $request,$billId){
        $supplier=Auth::user();
        $bill=Bill::where('id',$billId)->first();
        $bill->update([
            'status'=>'ملغية',
            'rejection_reason'=>$request->reason,

        ]);
        $bill->save();
        return $this->sudResponse('bill has been rejected');
    }

    public function accept($billId){
        $supplier=Auth::user();
        $bill=Bill::where('id',$billId)->first();
        $bill->update([
            'status'=>'قيد التحضير',

        ]);
        $bill->save();
        return $this->sudResponse('bill has been accepted');
    }

}
