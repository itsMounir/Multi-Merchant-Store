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

    public function Paid_Bill(Request $request){

        $supplier = Auth::user();
        $billCount = $supplier->bills()
            ->where('status', 'تم التوصيل')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->count();
        return $this->sudResponse($billCount);
    }





    public function getMarketsCount(Request $request){
        $supplier = Auth::user();
        $marketsCount = $supplier->bills()
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
            ->with('market')
            ->get()
            ->unique('market_id')
            ->count();
        return $this->sudResponse( $marketsCount);
    }



    public function getAverageBillPrice(Request $request){
        $supplier = Auth::user();
        $averageBillPrice = $supplier->bills()
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
            ->where('status', 'تم التوصيل')
            ->avg('total_price');
        return $this->indexOrShowResponse('message',$averageBillPrice);
    }




    public function getDeliveredProductPrice(Request $request)
    {
        $supplier = Auth::user();
        $totalPrice = $supplier->deliveredProductPrice($request->start_date,$request->end_date);
       return $this->indexOrShowResponse('message',$totalPrice);
    }



    public function Wasted_Bill(Request $request){
        $supplier=Auth::user();
        $billCount = $supplier->bills()
        ->where('status', ['ملغية','رفض الاستلام'])
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->count();
        return $this->sudResponse( $billCount);
    }



    public function Recived_Bill(Request $request){
        $supplier=Auth::user();
        $billCount=$supplier->bills()
        ->where('status','جديد')
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->count();
        return $this->sudResponse($billCount);

    }



}
