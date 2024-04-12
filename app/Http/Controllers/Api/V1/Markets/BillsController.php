<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Markets\{
    StoreBillRequest,
    UpdateBillRequest
};
use App\Models\{
    Bill,
    Supplier
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

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
    public function index()
    {
        $bills = Auth::user()->bills()->where('status', 'غير مدفوع')->get();
        return $this->indexOrShowResponse('bills', $bills);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $market = Auth::user();
            $supplier = Supplier::find($request->supplier_id);
            $supplier_products = $supplier->products->toArray();
            $total_price = 0.0;

            foreach ($request->cart as $product) {
                foreach ($supplier_products as $supplier_product) {
                    if ($product['id'] == $supplier_product['id']) {
                        $total_price += $supplier_product['pivot']['price'] * $product['quantity'];
                    }
                }
            }

            $bill = Bill::create([
                'total_price' => $total_price,
                'payement_method_id' => $request->payement_method_id,
                'supplier_id' => $supplier->id,
                'market_id' => $market->id,
                'discount_code' => $request->discount_code ? $request->discount_code : null,
            ]);

            foreach ($request->cart as $item) {
                $bill->products()->syncWithoutDetaching([
                    $item['id'] => [
                        'quantity' => $item['quantity'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
            return $this->sudResponse('Bill Created Successfully',201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        return $this->indexOrShowResponse('bill',$bill);
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
        return DB::transaction(function () use ($request,$bill) {
            $market = Auth::user();
            $supplier = Supplier::find($request->supplier_id);
            $supplier_products = $supplier->products->toArray();
            $total_price = 0.0;

            foreach ($request->cart as $product) {
                foreach ($supplier_products as $supplier_product) {

                    if ($product['id'] == $supplier_product['id']) {
                        $total_price += $supplier_product['pivot']['price'] * $product['quantity'];
                    }
                }
            }

            $bill->update([
                'total_price' => $total_price,
                'payement_method_id' => $request->payement_method_id ? $request->payement_method_id : $bill->payement_method_id,
                'supplier_id' => $supplier->id,
                'market_id' => $market->id,
                'discount_code' => $request->discount_code ? $request->discount_code : $bill->discount_code,
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
    public function destroy(Bill $bill)
    {
        return DB::transaction(function () use ($bill) {
            $bill->delete();
            //$bill->save();

            return $this->sudResponse('Bill Deleted Successfully');
        });

    }
}
