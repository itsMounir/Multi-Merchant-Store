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
    PayementMethod
};
use App\Services\BillsServices;
use Illuminate\Http\Request;
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
    public function __construct()
    {
        $this->authorizeResource(Bill::class, 'bill');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BillsFilters $billsFilters)
    {
        $bills = $billsFilters->applyFilters(Auth::user()->bills()->getQuery())->get();
        return $this->indexOrShowResponse('bills', $bills);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $payement_methods = PayementMethod::get(['id', 'name']);
        return $this->indexOrShowResponse('payement_methods', $payement_methods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $market = Auth::user();
            $bills = $request->bills;

            $billService = new BillsServices;
            foreach ($bills as $bill) {
                $billService->process($bill, $market);
            }

            return $this->sudResponse('Bills Created Successfully', 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        return $this->indexOrShowResponse('bill', $bill);
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
            $billService = new BillsServices;
            $market = Auth::user();
            $supplier = Supplier::find($request->supplier_id);

            $total_price = $billService->calculatePrice($updated_bill, $supplier);

            $billService->checkSupplierRequirements($supplier, $updated_bill, $total_price);

            $total_price -= $billService->supplierDiscount($supplier, $total_price);

            $bill->update([
                'total_price' => $total_price,
                'payement_method_id' => $updated_bill['payement_method_id'] ?? $bill->payement_method_id,
                'supplier_id' => $supplier->id,
                'market_id' => $market->id,
                'market_note' => $updated_bill['market_note'] ?? $bill->market_note,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        return DB::transaction(function () use ($bill) {
            $bill->delete();
            return $this->sudResponse('Bill Deleted Successfully');
        });

    }
}
