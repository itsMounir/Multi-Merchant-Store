<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Markets\{
    StoreBillRequest,
    UpdateBillRequest
};
use App\Models\{
    Bill,
    Supplier,
    PaymentMethod
};
use App\Services\BillsServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Filters\Markets\BillsFilters;

class BillsController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct(protected BillsServices $billsServices)
    {
        $this->authorizeResource(Bill::class, 'bill');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BillsFilters $billsFilters): JsonResponse
    {
        $bills = $billsFilters->applyFilters(Auth::user()->bills()->getQuery())
            ->with(['products', 'supplier'])
            ->where('created_at', '>=', Carbon::now()->subMonths(2))
            ->latest()
            ->get()
            ->append('total_price_after_discount');

        $bills->each(function ($bill) {
            $bill->supplier->append('min_bill_price');
        });

        return $this->indexOrShowResponse('bills', $bills);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        $payment_methods = PaymentMethod::get(['id', 'name']);
        return $this->indexOrShowResponse('payment_methods', $payment_methods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $market = Auth::user();
            $bills = $request->bills;
            $discount_messages = [];

            foreach ($bills as $bill) {
                $discount_messages[] = $this->billsServices->process($bill, $market);
            }
            return response()->json([
                'message' => '.تم إنشاء الفواتير بنجاح',
                'discount_messages' => $discount_messages,
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        // return $this->indexOrShowResponse('bill', $bill->load('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        return DB::transaction(function () use ($request, $bill) {
            $bill->products()->detach();
            $updated_bill = $request->all();
            $market = Auth::user();
            $supplier = Supplier::find($request->supplier_id)->append(['min_bill_price', 'image']);

            $total_price = $this->billsServices->calculatePrice($updated_bill, $supplier);

            $this->billsServices->checkSupplierRequirements($supplier, $updated_bill, $total_price);

            $supplier_discount = $this->billsServices->supplierDiscount($supplier, $total_price);

            $bill->update([
                'total_price' => $total_price,
                'goal_discount' => $supplier_discount,
                'payment_method_id' => $updated_bill['payment_method_id'] ?? $bill->payment_method_id,
                'supplier_id' => $supplier->id,
                'market_id' => $market->id,
                'market_note' => $updated_bill['market_note'] ?? $bill->market_note,
            ]);

            foreach ($updated_bill['products'] as $product) {
                $bill->products()->syncWithoutDetaching([
                    $product['id'] => [
                        'quantity' => $product['quantity'],
                        'buying_price' => $product['buying_price'],
                        'max_selling_quantity' => $product['max_selling_quantity'],
                        'has_offer' => $product['has_offer'],
                        'offer_buying_price' => $product['offer_buying_price'],
                        'max_offer_quantity' => $product['max_offer_quantity'],
                        'offer_expires_at' => $product['offer_expires_at'],
                        'created_at' => $bill->created_at,
                        'updated_at' => now(),
                    ],
                ]);
            }


            $bill->save();

            return $this->sudResponse('.تم تعديل الفاتورة بنجاح');
        });

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        return DB::transaction(function () use ($bill) {
            $bill->delete();
            return $this->sudResponse('.تم حذف الفاتورة بنجاح');
        });

    }
}
