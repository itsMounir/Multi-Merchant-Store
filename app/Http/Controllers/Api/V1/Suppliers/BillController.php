<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Bill,
    Supplier,
    Market

};
use App\Traits\Responses;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

use App\Services\BillsServices;
class BillController extends Controller
{
    use Responses;
    public function index(Request $request)
    {
        $supplier = Auth::user();
        $bills = $supplier->bills()->with(['market', 'products'])
        ->status($request->status)->get();
        return $this->indexOrShowResponse('message',$bills);
    }




    public function update(Request $request, Bill $bill)
{
    return DB::transaction(function () use ($request, $bill) {
        $billService = new BillsServices;
        $supplier = Auth::user();
        $market = Market::find($request->market_id);

        $total_price = $billService->calculatePrice($bill, $market);

        $total_price -= $billService->supplierDiscount($market, $total_price);

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

        return $this->sudResponse('Bill Updated Successfully');
    });
}


    public function reject(Request $request,$billId){
        $supplier=Auth::user();
        $bill=Bill::where('id',$billId)->first();
        $bill->update([
            'status'=>'ملغية',
            'rejection_reason'=>$request->reason,

        ]);
        return $this->sudResponse('Done');
    }

    public function accept(Request $request,$billId){
        $supplier=Auth::user();
        $bill=Bill::where('id',$billId)->first();
        $bill->update([
            'status'=>'قيد التحضير',
            'delivery_duration'=>$request->duration,
        ]);
        return $this->sudResponse('Done');
    }

    public function recive(Request $request,$billId){
        $supplier=Auth::user();
        $bill=Bill::where('id',$billId)->first();
        $bill->update([
            'status'=>'تم التوصيل',
            'recieved_price'=>$request->recieved_price,
        ]);
        return $this->sudResponse('Done');
    }



}
