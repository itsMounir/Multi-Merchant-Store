<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\BillPreparingMarket;
use App\Notifications\ReciveBillMarket;
use App\Models\{

    Bill,
    Supplier,
    Market
};
use Carbon\Carbon;

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
use App\Services\MobileNotificationServices;
class BillController extends Controller
{
    use Responses;
    public function index(Request $request)
    {
        $supplier = Auth::user();
        $billsQuery = $supplier->bills()->with(['products.category','market.city', 'supplier'])
                          ->status($request->status)->orderByInvoiceIdDesc();
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


    public function show($billId){
        $supplier = Auth::user();
        $bill = $supplier->bills()->with(['market.city', 'supplier', 'products.category'])->find($billId);

        if (!$bill) {
            return $this->indexOrShowResponse('Not found', 404);
        }
        $productIds = $bill->products->pluck('id');
        $bill->load([
            'products'
        ]);

        return $this->indexOrShowResponse('body', $bill);
    }



    public function update(UpdateBillRequest $request, Bill $bill)
    {


        return DB::transaction(function () use ($request, $bill) {
            if ($bill->status != 'جديد') {

                return $this->sudResponse('يمكنك تعديل فقط الفواتير التي حالتها جديد',403);
            }
            $supplier = Auth::user();

            $updated_bill = $request->all();
            $billService = new BillsServices;

            $total_price = $billService->calculatePrice($updated_bill, $supplier);
            $total_price -= $billService->marketDiscount(Market::find($bill->market_id), $total_price);
            $mario=$billService-> checkProductAvailability($updated_bill,$supplier,$bill);
            if ($mario) {
                return $this->sudResponse($mario, 200);
            }
            $bill->products()->detach();

            $delivery_duration = $request->input('delivery_duration');
            $bill->update([
                'total_price' => $total_price,
                'status' => 'قيد التحضير',
                'updated_at'=> Carbon::now(),
                'delivery_duration' => $delivery_duration ?: $bill->delivery_duration,
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
            $market = Market::find($bill->market_id);
            $market->notify(new BillPreparingMarket($bill, $supplier));
            $notification=new  MobileNotificationServices;
            $notification->sendNotification($market->deviceToken,"تحديث الفاتورة","أصبحت فاتورتك قيد التحضير من عند  " . $supplier->store_name . ".");
            $bill->save();

            return $this->sudResponse('تم تحديث الفاتورة بنجاح');
        });

    }




    public function reject(Request $request,$billId){
        $notification=new MobileNotificationServices;
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        if(!$bill){
            return $this->sudResponse('غير موجود');
        }
        $validatedData = $request->validate([
            'rejection_reason' => 'required',
            ]);
        $bill->update([
            'status'=>'ملغية',
            'rejection_reason'=>$request->rejection_reason,

        ]);
        $market = Market::find($bill->market_id);
        if ($market) {
             Notification::send($market, new RejectedNotification($supplier));
             $notification->sendNotification($market->deviceToken,"رفض فاتورة","تم رفض فاتورتك من عند ". $supplier->store_name . ".");
        }
        return $this->sudResponse('تم بنجاح');
    }



    /*public function accept(Request $request,$billId){
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $validatedData = $request->validate([
            'delivery_duration' => 'required',
            ]);
        $bill->update([
            'status'=>'قيد التحضير',
            'delivery_duration'=>$validatedData['delivery_duration'],
        ]);
        return $this->sudResponse('تم بنجاح');
    }*/


    public function recive(Request $request,$billId){
        $notification=new MobileNotificationServices;
        $supplier=Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        if(!$bill){
            return $this->sudResponse('غير موجود');
        }
        $validatedData = $request->validate([
            'recieved_price' => 'required',
            ]);
            if ($request['recieved_price'] > $bill->total_price) {
                return $this->sudResponse('سعر الاستلام يجب أن يكون اقل أو يساوي سعر الفاتورة');
            }
        $bill->update([
            'status'=>'تم التوصيل',
            'recieved_price'=>$request['recieved_price'],
        ]);
        $market = Market::find($bill->market_id);
        if ($market) {
             Notification::send($market,new ReciveBillMarket($supplier));
             $notification->sendNotification($market->deviceToken,"استلام فاتورة","تم  توصيل فاتورتك من عند ". $supplier->store_name . ".");
        }
        return $this->sudResponse('تم بنجاح');
    }


    public function Refused(Request $request, $billId){
        $supplier = Auth::user();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        if(!$bill){
            return $this->sudResponse('غير موجود');
        }
        $validatedData = $request->validate([
            'rejection_reason' => 'required',
        ]);
        foreach ($bill->products as $product) {
            $productSupplier = $product->suppliers()->where('supplier_id', $supplier->id)->first();
            if ($productSupplier) {
                $productSupplier->pivot->quantity += $product->pivot->quantity;
                if ($productSupplier->pivot->is_available == 0) {
                    $productSupplier->pivot->is_available = 1;
                }
                $productSupplier->pivot->save();
            }
        }

        $bill->update([
            'status' => 'رفض الاستلام',
            'rejection_reason' => $validatedData['rejection_reason'],
        ]);

        return $this->sudResponse('تم بنجاح');
    }



}
