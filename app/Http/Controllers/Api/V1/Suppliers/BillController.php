<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\BillPreparingMarket;
use App\Notifications\ReciveBillMarket;
use App\Models\{

    Bill,
    Supplier,
    Market,
    User
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
use App\Notifications\{updateStatusBill,RejectedNotification,Reject};
use Illuminate\Support\Facades\Notification;
use App\Services\BillsServices;
use App\Traits\FirebaseNotification;

class BillController extends Controller
{
    use Responses, FirebaseNotification;
    public function index(Request $request)
    {
        $supplier = Auth::user();
        $billsQuery = $supplier->bills()->with(['products.category', 'market.city', 'supplier'])
            ->status($request->status)->orderByInvoiceIdDesc();
        $bills = $billsQuery->get();
        $Count = $bills->count();
        $newBillsCount = Bill::newStatusCount($supplier->id);
        $results = [];
        foreach ($bills as $bill) {
            $productIds = $bill->products->pluck('id');
            $bill->load([
                'products'
            ])->append('total_price_after_discount');
            $results[] = $bill;
        }

        $billsData = [
            'Count' => $Count,
            'New_bill_count' => $newBillsCount,
            'bills' => $results
        ];

        return $this->indexOrShowResponse('body', $billsData);
    }


    public function show($billId)
    {
        $supplier = Auth::user();
        $bill = $supplier->bills()->with(['market.city', 'supplier', 'products.category'])->find($billId);

        if (!$bill) {
            return $this->indexOrShowResponse('Not found', 404);
        }
        $productIds = $bill->products->pluck('id');
        $bill->load([
            'products'
        ])->append('total_price_after_discount');

        return $this->indexOrShowResponse('body', $bill);
    }



    public function update(UpdateBillRequest $request, Bill $bill)
    {
        $supervisor = User::role('supervisor')->get();
        $admin = User::role('admin')->get();
        //return $bill;
        return DB::transaction(function () use ($request, $bill,$admin,$supervisor) {
            if ($bill->status != 'جديد') {

                return $this->sudResponse('يمكنك تعديل فقط الفواتير التي حالتها جديد', 403);
            }
            $supplier = Auth::user();

            $updated_bill = $request->all();
            $billService = new BillsServices;
            $updated_bill=$billService->removeProducts($updated_bill,$bill);
            //return $updated_bill;
            $total_price = $billService->calculatePriceSupplier($updated_bill, $supplier,$bill->id);
           // return $total_price;
            //$total_price -= $billService->marketDiscount(Market::find($bill->market_id), $total_price);

            $mario = $billService->checkProductAvailability($updated_bill, $supplier, $bill);
            if ($mario) {
                return $this->sudResponse($mario, 200);
            }
            $delivery_duration = $request->input('delivery_duration');
            $bill->update([
                'total_price' => $total_price,
                'status' => 'قيد التحضير',
                'updated_at' => Carbon::now(),
                'delivery_duration' => $delivery_duration ?: $bill->delivery_duration,
            ]);


            foreach ($updated_bill['products'] as $product) {
                $bill->products()->updateExistingPivot($product['id'], [
                    'quantity' => $product['quantity'],
                    'updated_at' => now(),
                ]);
            }
            $market = Market::find($bill->market_id);
            $market->notify(new BillPreparingMarket($bill, $supplier));
            Notification::send($supervisor, new updateStatusBill($supplier,'قيد التحضير',$market));
            Notification::send($admin, new updateStatusBill($supplier,'قيد التحضير',$market));
           // $this->sendNotification($market->deviceToken, "تحديث الفاتورة", "أصبحت فاتورتك قيد التحضير من عند  " . $supplier->store_name . ".");
            $bill->save();

            return $this->sudResponse('تم تحديث الفاتورة بنجاح');
        });
    }




    public function reject(Request $request, $billId)
    {
        $supplier = Auth::user();
        $supervisor = User::role('supervisor')->get();
        $admin = User::role('admin')->get();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        if (!$bill) {
            return $this->sudResponse('غير موجود');
        }
        $validatedData = $request->validate([
            'rejection_reason' => 'required',
        ]);
        $bill->update([
            'status' => 'ملغية',
            'rejection_reason' => $request->rejection_reason,

        ]);
        $market = Market::find($bill->market_id);
        if ($market) {
            Notification::send($supervisor, new updateStatusBill($supplier,'ملغية',$market));
            Notification::send($admin, new updateStatusBill($supplier,'ملغية',$market));
            Notification::send($market, new RejectedNotification($supplier));
           // $this->sendNotification($market->deviceToken, "رفض فاتورة", "تم رفض فاتورتك من عند " . $supplier->store_name . ".");
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


    public function recive(Request $request, $billId)
    {
        $supplier = Auth::user();
        $supervisor = User::role('supervisor')->get();
        $admin = User::role('admin')->get();

        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        if (!$bill) {
            return $this->sudResponse('غير موجود');
        }

        $validatedData = $request->validate([
            'recieved_price' => 'required',
        ]);
        /*if ($request['recieved_price'] > $bill->total_price) {
            return $this->sudResponse('سعر الاستلام يجب أن يكون اقل أو يساوي اجمالي الفاتورة');
        }*/
        $bill->update([
            'status' => 'تم التوصيل',
            'recieved_price' => $request['recieved_price'],
        ]);

        $market = Market::find($bill->market_id);
        if ($market) {
            Notification::send($supervisor, new updateStatusBill($supplier,'تم التوصيل',$market));
            Notification::send($admin, new updateStatusBill($supplier,'تم التوصيل',$market));
            Notification::send($market, new ReciveBillMarket($supplier));
            //$this->sendNotification($market->deviceToken, "استلام فاتورة", "تم  توصيل فاتورتك من عند " . $supplier->store_name . ".");
        }

        return $this->sudResponse('تم بنجاح');
    }


    public function Refused(Request $request, $billId)
    {
        $supplier = Auth::user();
        $admin = User::role('admin')->get();
        $bill = Bill::where('id', $billId)->where('supplier_id', $supplier->id)->first();
        $market = Market::find($bill->market_id);
        if (!$bill) {
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
        Notification::send($admin, new Reject($supplier,$request->rejection_reason,$market));


        $bill->update([
            'status' => 'رفض الاستلام',
            'rejection_reason' => $validatedData['rejection_reason'],
        ]);

        return $this->sudResponse('تم بنجاح');
    }


    public function count_bill_price(){
        $bills = Bill::where('status', 'تم التوصيل')
             ->whereDate('created_at', '>=', '2024-10-01')
             ->whereDate('created_at', '<=', now())
             ->get();
        $price=0;
        foreach($bills as $bill){
            $price=$price+$bill->total_price_after_discount;

        }
        $data=[$price,$bills->count()];
        return $data;
    }
}
