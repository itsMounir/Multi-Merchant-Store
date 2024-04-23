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
        $newBillsCount = Bill::newStatusCount();
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

                return $this->sudResponse('you can  update bills whose status is New',403);
            }
            $bill->products()->detach();
            $updated_bill = $request->all();
            $billService = new BillsServices;
            $supplier = Auth::user();
            $total_price = $billService->calculatePrice($updated_bill, $supplier);
            $total_price -= $billService->supplierDiscount($supplier, $total_price);

            $bill->update([
                'total_price' => $total_price,
                'payement_method_id' => $updated_bill['payement_method_id'] ?? $bill->payement_method_id,
            ]);

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

            return $this->sudResponse('Bill Updated Successfully');
        });

    }




    public function reject(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'delivery_duration' => 'required',
            ]);
        $bill->update([
            'status'=>'ملغية',
            'rejection_reason'=>$request->reason,

        ]);
        return $this->sudResponse('Done');
    }



    public function accept(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'delivery_duration' => 'required',
            ]);
        $bill->update([
            'status'=>'قيد التحضير',
            'delivery_duration'=>$validatedData['delivery_duration'],
        ]);
        return $this->sudResponse('Done');
    }


    public function recive(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
        'recieved_price' => 'nullable|numeric',
        ]);
        $bill->update([
            'status'=>'تم التوصيل',
            'recieved_price'=>$validatedData['recieved_price'],
        ]);
        return $this->sudResponse('Done');
    }



}
