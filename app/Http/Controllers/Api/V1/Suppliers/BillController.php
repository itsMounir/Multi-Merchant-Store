<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Supplier;
use App\Traits\Responses;
use Illuminate\Support\Facades\Auth;
class BillController extends Controller
{
    use Responses;
    public function index()
    {
        $supplier = Auth::user();
        if (!$supplier) {
            return $this->sudResponse('unauthorized',401);
        }
        $bills = $supplier->bills()->with(['market', 'products'])->get();
        return $this->indexOrShowResponse('message',$bills);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(string $id)
    {

    }


    public function edit(string $id)
    {

    }


    public function update(Request $request, $bill_id)
    {
        $supplier = Auth::user();
        $bill = $supplier->bills()->with('products')->where('status', '=', 'غير مدفوع')->find($bill_id);

        if (!$bill) {
            return $this->sudResponse('Not found',404);
        }

        $this->detachProducts($bill, $request->input('products_to_remove'));
        $this->updateProductQuantities($bill, $request->input('products_to_update'));
        return $this->sudResponse('Bill has been updated');
    }


//ازالة المنتجات من الفاتورة
    private function detachProducts($bill, array $productsToRemove)
    {
        if (!empty($productsToRemove)) {
            $bill->products()->detach($productsToRemove);
        }
    }

// تعديل على كمية المنتجات

    private function updateProductQuantities($bill, array $productsToUpdate)
    {
        foreach ($productsToUpdate as $productId => $quantity) {
            $quantity > 0 ? $bill->products()->updateExistingPivot($productId, ['quantity' => $quantity]) : $bill->products()->detach($productId);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($billId)
    {
        $supplier = Auth::user();
        $bill = $supplier->bills()->where('id', $billId)->where('status', '=', 'غير مدفوع')->first();
        if (!$bill) {
            return $this->sudResponse('Not found',404);
        }
        $bill->delete();
        return $this->indexOrShowResponse('message','Bill has been deleted');

    }
}
