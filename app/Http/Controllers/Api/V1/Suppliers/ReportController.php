<?php

namespace App\Http\Controllers\Api\V1\Suppliers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Market,
    ProductSupplier,
    Supplier,
    Bill

};
use App\Traits\Responses;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
class ReportController extends Controller
{
    use Responses;

    public function reports(Request $request){
        $supplier = Auth::user();


        $paidBillCount = $supplier->bills()
            ->where('status', 'تم التوصيل')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->count();


        $marketsCount = $supplier->bills()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->with('market')
            ->get()
            ->unique('market_id')
            ->count();


        $averageBillPrice = $supplier->bills()
            ->where('status', 'تم التوصيل')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->avg('total_price');


        $totalPrice = $supplier->deliveredProductPrice($request->start_date, $request->end_date);


        $wastedBillCount = $supplier->bills()
            ->whereIn('status', ['ملغية','رفض الاستلام'])
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->count();


        $receivedBillCount = $supplier->bills()
            ->where('status', 'جديد')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->count();


        $responseData = [
            'paid_Bill' => $paidBillCount,
            'markets_Count' => $marketsCount,
            'average_Bills' => $averageBillPrice,
            'total_Price' => $totalPrice,
            'wasted_Bill' => $wastedBillCount,
            'received_Bill' => $receivedBillCount
        ];


        return $this->indexOrShowResponse('message',$responseData);
    }




}
