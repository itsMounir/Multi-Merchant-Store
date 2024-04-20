<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Bill;

use Illuminate\Http\Request;

class BillsController extends Controller
{
    /**
     * To accept or declain bill
     * @param string $id  
     * @param string $status
     * @return JsonResponse 
     */
    public function billDecision($id, Request $request)
    {
        $bill = Bill::findOrFail($id);
        if ($bill->status != 'انتظار')
            return response()->json(['message' => 'you cant change the status for this bill.... already done '], 422);
        if ($request->status == 'تأكيد') {
            $bill->status = 'جديد';
            $bill->save();
            return $this->sudResponse('order has been accepted and sent to the Market', 200);
        } elseif ($request->status == 'إلغاء') {
            $bill->status = 'ملغية';
            $bill->save();
            return $this->sudResponse('order has been declained ', 200);
        }
        return response()->json(['message' => 'something went wrong... please check _billDecision_'],);
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
        $query = Bill::query()->with(['products' => function ($query) {
            $query->withTrashed(); 
        }]);

        if ($withFee == '1' || $withFee == '0')
            $query->where('has_additional_cost', $withFee);
        $bills = $query->where('status', '!=', 'انتظار')->get();
        return response()->json(['bills' => $bills]);
    }

    public function show($id)
    {
        $bill = Bill::with(['products' => function ($query) {
            $query->withTrashed();
        }])->findOrFail($id);
        return response()->json(['bill' => $bill]);
    }
}
