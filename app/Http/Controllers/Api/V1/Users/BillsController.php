<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Supplier;
use App\Traits\FirebaseNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillsController extends Controller
{
    use FirebaseNotification;

    /**
     * To accept a new bill
     * @param string $id
     * @return JsonResponse
     */
    public function acceptBill(String $id)
    {
        try {
            $bill = Bill::with(['market.category', 'supplier.category', 'products' => function ($query) {
                $query->withTrashed()->with('category');
            }])->findOrFail($id);

            $this->authorize('webUpdate', $bill);

            if ($bill->status != 'انتظار')
                return response()->json(['message' => 'you can`t accept this bill... it is alredy accepted or canceled'], 422);
            $bill->status = "جديد";
            $bill->save();
            $bill->supplier->makeHidden('min_bill_price');
            // send notifications
            $marketDeviceToken = $bill->market->deviceToken;
            $supplierDeviceToken = $bill->supplier->deviceToken;

            $this->sendNotification($marketDeviceToken, 'الموفراتي', 'تم قبول فاتورتك');
            $this->sendNotification($supplierDeviceToken, 'الموفراتي', 'لديك فاتورة جديدة');

            return response()->json(['messge' => 'Bill Accepted', 'bill' => $bill], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * To declain a new bill
     * @param string $id
     * @return JsonResponse
     */
    public function cancelBill($id)
    {
        try {
            $bill = Bill::with(['market.category', 'supplier.category', 'products' => function ($query) {
                $query->withTrashed()->with('category');
            }])->findOrFail($id);

            $this->authorize('webUpdate', $bill);

            if ($bill->status != 'انتظار')
                return response()->json(['message' => 'you can`t cancel this bill... it is alredy accepted or canceled'], 422);
            $bill->status = "ملغية";
            $bill->save();
            $bill->supplier->makeHidden('min_bill_price');
            //send notification to market
            $marketDeviceToken = $bill->market->deviceToken;
            $this->sendNotification($marketDeviceToken, 'الموفراتي', 'عذراً, تم رفض فاتورتك');

            return response()->json(['messgae' => 'Bill canceled', 'bill' => $bill], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the Bills filterd on status 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 20);
        $status = $request->input('status');
        $order_by = $request->input('order_by', 'created_at');
        $order = $request->input('order', 'asc');
        $query = Bill::orderBy($order_by, $order)->with('market.category', 'supplier.category', 'products.category');
        if ($status)
            $query->where('status', $status);
        $bills = $query->paginate($per_page, ['*'], 'p')->through(function ($bill) {
            $bill->supplier->makeHidden('min_bill_price');
            $bill->append('total_price_after_discount');
            return $bill;
        });
        return response()->json($bills, 200);
    }


    /**
     * To show new bills
     * @return JsonResponse
     */
    public function newBills()
    {
        $this->authorize('webViewAny', Bill::class);
        $bills = Bill::with('market.category', 'supplier.category', 'products.category')->where('status', 'انتظار')->get();
        $bills->each(function ($bill) {
            $bill->supplier->makeHidden('min_bill_price');
        });
        return response()->json(['bills' => $bills]);
    }

    /**
     * To show old bills
     * @param Request $cost
     * @return JsonResponse
     */
    public function oldBills(Request $request)
    {
        $this->authorize('webViewAny', Bill::class);

        $withFee = $request->query('fee');
        $query = Bill::query()->with(['market.category', 'supplier.category', 'products' => function ($query) {
            $query->withTrashed()->with('category');
        }]);

        if ($withFee == '1' || $withFee == '0')
            $query->where('has_additional_cost', $withFee);
        $bills = $query->where('status', '!=', 'انتظار')->get();
        $bills->each(function ($bill) {
            $bill->supplier->makeHidden('min_bill_price');
        });
        return response()->json(['bills' => $bills]);
    }


    /**
     * To get Bill by ID
     * @param string $id
     * @return JsonResponse
     */
    public function show(String $id)
    {
        $bill = Bill::with(['market', 'supplier'])->findOrFail($id);
        $this->authorize('webView', $bill);
        $supplier = Supplier::findOrFail($bill->supplier_id);
        $productIds = $bill->products->pluck('id');
        $bill->load([
            'products' => function ($query) use ($productIds, $supplier) {
                $query->whereIn('products.id', $productIds)
                    ->join('product_supplier', 'products.id', '=', 'product_supplier.product_id')
                    ->where('product_supplier.supplier_id', $supplier->id)
                    ->select('products.*', 'product_supplier.price as price');
            }

        ]);
        $bill->supplier->makeHidden('min_bill_price');
        return response()->json(['bill' => $bill]);
    }

    /**
     * Display list of bills that the supplier or the market in touch are match the inserted name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $this->authorize('webViewAny', Bill::class);
        try {
            $name = $request->query('name');
            if (!$name) {
                return response()->json(['error' => 'Store name is required'], 400);
            }
            $bills = Bill::getBySupplierStoreName($name);
            return response()->json($bills, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
