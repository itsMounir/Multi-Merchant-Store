<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BillsController extends Controller
{

    /**
     * To accept a new bill
     * @param string $id
     * @return JsonResponse
     */
    public function acceptBill(String $id)
    {
        $bill = Bill::with(['products' => function ($query) {
            $query->withTrashed()->with('category');
        }, 'market.category', 'supplier.category'])->findOrFail($id);

        $this->authorize('webUpdate', $bill);

        if ($bill->status != 'انتظار')
            return response()->json(['message' => 'you can`t accept this bill... it is alredy accepted or canceled'], 422);
        $bill->status = "جديد";
        $bill->save();
        return response()->json(['messge' => 'Bill Accepted', 'bill' => $bill], 200);
    }

    /**
     * To declain a new bill
     * @param string $id
     * @return JsonResponse
     */
    public function cancelBill($id)
    {
        $bill = Bill::with(['products' => function ($query) {
            $query->withTrashed()->with('category');
        }, 'market.category', 'supplier.category'])->findOrFail($id);

        $this->authorize('webUpdate', $bill);

        if ($bill->status != 'انتظار')
            return response()->json(['message' => 'you can`t cancel this bill... it is alredy accepted or canceled'], 422);
        $bill->status = "ملغية";
        $bill->save();
        return response()->json(['messgae' => 'Bill canceled', 'bill' => $bill], 200);
    }
    /**
     * To show new bills
     * @return JsonResponse
     */
    public function newBills()
    {
        $this->authorize('webViewAny', Bill::class);
        $bills = Bill::with('products.category', 'market.category', 'supplier.category')->where('status', 'انتظار')->get();
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
        $query = Bill::query()->with(['products' => function ($query) {
            $query->withTrashed()->with('category');
        }, 'market.category', 'supplier.category']);

        if ($withFee == '1' || $withFee == '0')
            $query->where('has_additional_cost', $withFee);
        $bills = $query->where('status', '!=', 'انتظار')->get();
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

        return response()->json(['bill' => $bill]);
    }


    /**
     * Display list of bills that the supplier or the market in touch are match the inserted name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $name = $request->query('name');
            // should get names of supplier and markets form the bills 
            $supplier = Bill::where('supplier_name', 'like', '%' . $name . '%')->orWhere('market_name', 'like', '%' . $name . '%')->get();
            return response()->json($supplier, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
