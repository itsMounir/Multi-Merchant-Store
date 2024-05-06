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
        $billsQuery = $supplier->bills()->with(['products.category','market.city', 'supplier'])
                          ->status($request->status);
        $bills = $billsQuery->get();
        $Count = $bills->count();
        $newBillsCount = Bill::newStatusCount($supplier->id);
        $results = [];
        foreach ($bills as $bill) {
            $productIds = $bill->products->pluck('id');
            $bill->load([
                'products' => function ($query) use ($productIds, $supplier) {
                    $query->whereIn('products.id', $productIds)
                          ->join('product_supplier', 'products.id', '=', 'product_supplier.product_id')
                          ->where('product_supplier.supplier_id', $supplier->id)
                          ->select('products.*', 'product_supplier.price as price');
                }
            ]);
            $results[] = $bill;
        }

        $billsData = [
            'Count' => $Count,
            'New_bill_count' => $newBillsCount,
            'bills' => $results
        ];

        return $this->indexOrShowResponse('body', $billsData);
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
                'status'=>'تم القبول',
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
            ]);
        $bill->update([
            'status'=>'ملغية',
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
            ]);
        $bill->update([
            'status'=>'جاري التحضير',
            'delivery_duration'=>$validatedData['delivery_duration'],
        ]);
        return $this->sudResponse('تم بنجاح');
    }


    public function recive(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'recieved_price' => 'required',
            ]);
        $bill->update([
            'status'=>'تم التوصيل',
            'recieved_price'=>$request['recieved_price'],
        ]);
        return $this->sudResponse('تم بنجاح');
    }



    public function Refused(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'rejection_reason' => 'required',
            ]);
        $bill->update([
            'status'=>'رفض الاستلام',
            'rejection_reason'=>$validatedData['rejection_reason'],
        ]);
        return $this->sudResponse('تم بنجاح');
    }



}
