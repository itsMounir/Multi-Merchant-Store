<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Bill,
    Supplier,
    Market
};
use App\Http\Requests\Api\V1\Markets\{
    StoreBillRequest,
    UpdateBillRequest
};
use App\Traits\Responses;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Notifications\RejectedNotification;
use Illuminate\Support\Facades\Notification;
use App\Services\BillsServices;
class BillController extends Controller
{
    use Responses;
    public function index(Request $request)
    {
        $supplier = Auth::user();
        $bills = $supplier->bills()->with(['market', 'products'])
        ->status($request->status)->get();
        $Count = $bills->count();
        $newBillsCount = Bill::newStatusCount($supplier->id);
        $billsData = [
            'Count' => $Count,
            'New_bill_count'=>$newBillsCount,
            'bills' => $bills
        ];
        return $this->indexOrShowResponse('message',$billsData);
    }




    public function update(UpdateBillRequest $request, Bill $bill)
    {

        return DB::transaction(function () use ($request, $bill) {
            if ($bill->status != 'جديد') {

                return $this->sudResponse('يمكنك تعديل فقط الفواتير التي حالتها جديد',403);
            }
            $bill->products()->detach();
            $updated_bill = $request->all();
            $billService = new BillsServices;
            $supplier = Auth::user();
            $total_price = $billService->calculatePrice($updated_bill, $supplier);
            $total_price -= $billService->marketDiscount(Market::find($bill->market_id), $total_price);

            $bill->update([
                'total_price' => $total_price,
            ]);
            //return $bill;

            foreach ($updated_bill['products'] as $item) {
                $bill->products()->syncWithoutDetaching([
                    $item['id'] => [
                        'quantity' => $item['quantity'],
                        'created_at' => $bill->created_at,
                        'updated_at' => now(),
                    ],
                ]);
            }

            $bill->save();

            return $this->sudResponse('تم تحديث الفاتورة بنجاح');
        });

    }




    public function reject(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'rejection_reason' => 'required',
            'status'=>'required',
            ]);
        $bill->update([
            'status'=>$request->status,
            'rejection_reason'=>$request->reason,

        ]);
        $market = Market::find($bill->market_id);
        if ($market) {
             Notification::send($market, new RejectedNotification($supplier));
        }
        return $this->sudResponse('تم بنجاح');
    }



    public function accept(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'delivery_duration' => 'required',
            'status'=>'required',
            ]);
        $bill->update([
            'status'=>$request->status,
            'delivery_duration'=>$validatedData['delivery_duration'],
        ]);
        return $this->sudResponse('تم بنجاح');
    }


    public function recive(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'recieved_price' => 'required',
            'status'=>'required',
            ]);
        $bill->update([
            'status'=>$request->status,
            'recieved_price'=>$request['recieved_price'],
        ]);
        return $this->sudResponse('تم بنجاح');
    }



}
