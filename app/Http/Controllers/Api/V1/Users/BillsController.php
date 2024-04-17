<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Bill;

use Illuminate\Http\Request;

class BillsController extends Controller
{
    /**
     * To accept or declain bill
     * @param ID $id  
     * @param string $status
     * @return JsonResponse 
     */
    public function billDecision($id, Request $request)
    {
        $bill = Bill::find($id);
        if (!$bill)
            return response()->json(['message' => 'Bill not found'], 404);
        if ($request->status == 'تأكيد') {
            $bill->status = 'جديد';
            $bill->save();
            return $this->sudResponse('order has been accepted and sent to the Market', 200);
        } elseif ($request->status == 'إلغاء') {
            $bill->status = 'ملغية';
            $bill->save();
            return $this->sudResponse('order has been declained ', 200);
        }
    }

    /**
     * To show new bills 
     * @return JsonResponse
     */
    public function newBills()
    {
        $bills = Bill::with('products')->where('status', 'انتظار')->get();
        return response()->json(['bills' => $bills]);
    }

    /**
     * To show old bills 
     * @param Request $cost
     * @return JsonResponse
     */
    public function oldBills(Request $request)
    {
        $withFee = $request->query('fee');
        $query = Bill::query();
        if ($withFee == 1 || $withFee == 0)
            $query->where('has_additional_cost', $withFee);
        $bills = $query->where('status', '!=', 'انتظار')->get();
        return response()->json(['bills' => $bills]);
    }
}
