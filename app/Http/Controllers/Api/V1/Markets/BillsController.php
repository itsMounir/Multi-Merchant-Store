<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Markets\StoreBillRequest;
use App\Models\Bill;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bills = Auth::user()->bills()->where('status','غير مدفوع')->get();
        return $this->indexOrShowResponse('bills',$bills);
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
        /**
         * supplier_id
         * add the condition for product exsisting in the store in the validation
         *
         */
        return DB::transaction(function () use($request) {
            //dd($request->cart);
            $market = Auth::user();
            $supplier = Supplier::find($request->supplier_id);
            $supplier_products = $supplier->products->toArray();
            $total_price = 0.0;
            //dd($request->cart);
            foreach ($request->cart as $product) {
                foreach ($supplier_products as $supplier_product) {
                    //dd($supplier_product);
                    if ($product['id'] == $supplier_product['id']) {
                        $total_price += $supplier_product['pivot']['price'] * $product['quantity'];
                    }
                }
            }
            //dd($total_price);
            $bill = Bill::create([
                'total_price' => $total_price,
                'payement_method_id' => $request->payement_method_id,
                'supplier_id' => $supplier->id,
                'market_id' => $market->id,
                'discount_code' => $request->discount_code ? $request->discount_code : null,
            ]);
            //dd($bill);
            // $bill->products()->sync([
            //     $request->cart['id'] => ['quantity' => $request->cart['quantity']],
            // ]);

            foreach ($request->cart as $item) {
                // Update the pivot table with the product ID and quantity
                $bill->products()->syncWithoutDetaching([
                    $item['id'] => ['quantity' => $item['quantity']],
                ]);
            }


            //$bill->save();

            return response()->json('Done');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        //
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
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
