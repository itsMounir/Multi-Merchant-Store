<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Market;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    public function getPublicStatistics(Request $request) {}

    /**
     * Count the bills and calculate the profite from the bills ( 60% AND 40% ) (BETWEEN TOW DATES)
     * @param Request $request start_date , end_date
     * @return JsonResponse 
     */
    public function getBillsInventory(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
        $bills = Bill::whereBetween('created_at', [$start_date, $end_date]);
        $bills_count = $bills->count();

        $bills_cancelled_count = (clone $bills)->whereIn('status', ['ملغية', 'رفض الاستلام'])->count();
        $total_waste_cost = $bills->sum('total_price');

        $bills_on_prepere_count = (clone $bills)->whereIn('status', ['انتظار', 'جديد', 'قيد التحضير'])->count();

        $bills_done = (clone $bills)->where('status', 'تم التوصيل');
        $bills_done_count = $bills_done->count();
        $total_cost = $bills_done->sum('total_price');

        $bills_with_5_pound_profit_count = (clone $bills_done)->whereDate('created_at', '<=', Carbon::parse('2024-9-6'))->where('has_additional_cost', 1)->count();
        $total_profit_5_pound = $bills_with_5_pound_profit_count * 5;
 
        $bills_with_1_point_25_percent_profit = (clone $bills_done)->whereDate('created_at', '>', Carbon::parse('2024-9-6'))->where('has_additional_cost', 1);
        $bills_with_1_point_25_percent_profit_count = $bills_with_1_point_25_percent_profit->count();
        $bills_with_1_point_25_percent_profit_total_cost = $bills_with_1_point_25_percent_profit->sum('total_price');
        $total_profit_1_point_25_percent = $bills_with_1_point_25_percent_profit->sum('total_price') * 0.0125;



        $bills_without_profit = (clone $bills_done)->where('has_additional_cost', 0);
        $bills_without_profit_count = $bills_without_profit->count();

        $bills_with_profit_count = $bills_with_5_pound_profit_count + $bills_with_1_point_25_percent_profit_count;

        $bills_with_discount = (clone $bills_done)->where('goal_discount', '>', '0');
        $bills_with_discount_count = $bills_with_discount->count();

        $total_discount = $bills_with_discount->sum('goal_discount');

        $total_profit = $total_profit_1_point_25_percent + $total_profit_5_pound;

        $_60_percetn_of_total_profit = $total_profit * 60 / 100;
        $_40_percent_of_total_profit = $total_profit * 40 / 100;

        return response()->json(['data' => [
            'bills_count' => $bills_count, //العدد الكلي للفواتير
            'bills_done_count' => $bills_done_count, // عدد الفواتير المنجزة
            'bills_cancelled_count' => $bills_cancelled_count, // عدد الفواتير الملغية
            'bills_on_prepere_count' => $bills_on_prepere_count, // عدد الفواتير قيد التجضير
            'total_cost' => $total_cost, // قيمة الفواتير المنجزة الكلية
            'total_waste_cost' => $total_waste_cost, // قيمة الفواتير الملغية

            'bills_without_profit_count' => $bills_without_profit_count, //عدد الفواتير الخالية من الربح
            'bills_with_profit_count' => $bills_with_profit_count, // عدد الفواتير الحاوية للربح
            'total_profit' => $total_profit, // قيمة الربح الكلية

            'bills_with_discount_count' => $bills_with_discount_count, // عدد الفواتير الحاوية للخصم
            'total_discount' => $total_discount, // القيمة المجملة للخصم

            'bills_5_pound_count' => $bills_with_5_pound_profit_count, // عدد الفواتير ذات الربح الثابت
            'bills_5_pound_profit' => $total_profit_5_pound, // القيمة المجملة للربح الثابت

            'bills_1.25%_count' => $bills_with_1_point_25_percent_profit_count, // عدد الفواتير ذات الربح النسبي
            'bills_1.25%_total_cost' => $bills_with_1_point_25_percent_profit_total_cost, // القيمة المجملة للفواتير
            'bills_1.25%_profit' => $total_profit_1_point_25_percent, // القيمة الجملة للربح


            '60%_of_the_total' => $_60_percetn_of_total_profit, // 60 % من الربح 
            '40%_of_the_total' => $_40_percent_of_total_profit, // 40%  من الربح
        ]], 200);
    }

    /**
     * get statistics of bills per week
     * @param Request $request
     * @return JsonResponse
     */
    public function getBillStatisticsPerWeek(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $bills = Bill::whereBetween('created_at', [$start_date, $end_date])
            ->select(DB::raw('WEEK(created_at) as week'), DB::raw('count(*) as count'), DB::raw('sum(total_price) as total_amount'))
            ->groupBy('week')
            ->get();
        $statistics = $bills->map(function ($item) {
            return [
                'week' => $item->week,
                'total_bills' => $item->count,
                'total_amount' => $item->total_amount,
            ];
        });
        return response()->json(['data' => $statistics], 200);
    }

    /**
     * get statistics of bills per month
     * @param Request $request
     * @return JsonResponse
     */
    public function getBillStatisticsPerMonth(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $bills = Bill::whereBetween('created_at', [$start_date, $end_date])
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'), DB::raw('sum(total_price) as total_amount'))
            ->groupBy('month')
            ->get();
        $statistics = $bills->map(function ($item) {
            return [
                'month' => $item->month,
                'total_bills' => $item->count,
                'total_amount' => $item->total_amount,
            ];
        });
        return response()->json(['data' => $statistics], 200);
    }

    /**
     * count the markets and suppliers users
     * @return JsonResponse
     */
    public function getMarketsAndSuppliersCount()
    {
        $this->authorize('viewAny', User::class);

        $markets = Market::all()->count();
        $suppliers = Supplier::all()->count();
        return response()->json(['data' => [
            'number of markets' => $markets,
            'number of suppliers' => $suppliers,
        ]], 200);
    }


    /**
     * Show the top three requested markets and top three orderes suppliers (THE ORDER IS NOT CANCELED)
     * @return JsonResponse
     */
    public function getTopThreeOrderingMarketsAndSuppliers()
    {
        $this->authorize('viewAny', User::class);

        $top_three_markets_with_orders = Market::withCount(['bills' => function ($query) {
            $query->where('status', '!=', 'ملغية');
        }])->orderBy('bills_count', 'desc')->take(3)->get();
        $top_three_suppliers_submitting_orders = Supplier::withCount(['bills' => function ($query) {
            $query->where('status', '!=', 'ملغية');
        }])->orderBy('bills_count', 'desc')->take(3)->get();
        $top_three_suppliers_submitting_orders->makeHidden('min_bill_price');

        return response()->json(['data' => [
            'top_three_requested_market_for_orders' => $top_three_markets_with_orders,
            'top_three_orderes_supplier' => $top_three_suppliers_submitting_orders,
        ]], 200);
    }

    /**
     * show the top three Markets and Suppliers canceling orders 
     * @return JsonResponse 
     */
    public function getTopThreeCancellingMarketsAndSuppliers()
    {
        $this->authorize('viewAny', User::class);

        $top_three_markets_with_orders = Market::withCount(['bills' => function ($query) {
            $query->where('status', 'LIKE', 'رفض الاستلام');
        }])->orderBy('bills_count', 'desc')->take(3)->get();
        $top_three_suppliers_submitting_orders = Supplier::withCount(['bills' => function ($query) {
            $query->where('status', 'LIKE', 'ملغية');
        }])->orderBy('bills_count', 'desc')->take(3)->get();
        $top_three_suppliers_submitting_orders->makeHidden('min_bill_price');

        return response()->json(['data' => [
            'top_three_requested_market_for_orders' => $top_three_markets_with_orders,
            'top_three_orderes_supplier' => $top_three_suppliers_submitting_orders,
        ]], 200);
    }
}
