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
        $bills = Bill::whereBetween('created_at', [$start_date, $end_date])->get();
        $bills_count = count($bills);
        $bills_with_additional_cost = $bills->where('has_additional_cost', 1);
        $bills_with_additional_cost_count = count($bills_with_additional_cost);
        $bills_without_additional_cost = $bills->where('has_additional_cost', 0);
        $bills_without_additional_cost_count = count($bills_without_additional_cost);
        $bills_with_discount = $bills->where('goal_discount', '>', '0');
        $bills_with_discount_count = $bills_with_discount->count();


        $total_cost = $bills->sum('total_price');
        $total_discount = $bills->sum('goal_discount');
        $total_additional_cost = $bills_with_additional_cost_count * 5;

        $_60_percetn_of_total_profit = $total_additional_cost * 60 / 100;
        $_40_percent_of_total_profit = $total_additional_cost * 40 / 100;

        return response()->json(['data' => [
            'bills_count' => $bills_count,

            'bills_with_additional_cost_count' => $bills_with_additional_cost_count,
            'bills_without_additional_cost_count' => $bills_without_additional_cost_count,
            'bills_with_discount_count' => $bills_with_discount_count,

            'total_cost' => $total_cost,
            'total_additional_cost ' => $total_additional_cost,
            'total_discount' => $total_discount,

            '60%_of_the_total' => $_60_percetn_of_total_profit,
            '40%_of_the_total' => $_40_percent_of_total_profit,
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
