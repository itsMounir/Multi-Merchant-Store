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

class StatisticsController extends Controller
{

    public function getPublicStatistics(Request $request)
    {
    }

    /**
     * Count the bills and calculate the profite from the bills ( 60% AND 40% ) (BETWEEN TOW DATES)
     * @param Request $request: start_date , end_date
     * @return JsonResponse 
     */
    public function getBillStatistics(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
        $bills = Bill::whereBetween('created_at', [$start_date, $end_date])->get();
        $bills_count = count($bills);
        $bills_with_additional_cost = $bills->where('has_additional_cost', 1);
        $bills_without_additional_cost = $bills->where('has_additional_cost', 0);
        $bills_with_additional_cost_count = count($bills_with_additional_cost);
        $bills_without_additional_cost_count = count($bills_without_additional_cost);

        $total_cost = $bills->sum('total_price');
        $total_additional_cost = $bills_with_additional_cost_count * 5;

        $_60_percetn_of_total_profit = $total_additional_cost * 60 / 100;
        $_40_percent_of_total_profit = $total_additional_cost * 40 / 100;

        return response()->json(['data' => [
            'bills_count' => $bills_count,

            'bills_with_additional_cost_count' => $bills_with_additional_cost_count,
            'bills_without_additional_cost_count' => $bills_without_additional_cost_count,

            'total_cost' => $total_cost,
            'total_additional_cost ' => $total_additional_cost,

            '60%_of_the_total' => $_60_percetn_of_total_profit,
            '40%_of_the_total' => $_40_percent_of_total_profit,
        ]], 200);
    }

    /**
     * count the market users (SUBSCRIBED AND NOT SUBSCRIBED)
     * @return JsonResponse
     */
    public function getMarketUsersStatistics()
    {
        $this->authorize('viewAny', User::class);

        $subscribed_users = Market::with('city', 'category')->where('is_subscribed', 1)->latest()->get();
        $unSubscribed_users = Market::with('city', 'category')->where('is_subscribed', 0)->latest()->get();
        $number_of_subscribed_users = count($subscribed_users);
        $number_of_unSubscribed_users = count($unSubscribed_users);
        return response()->json(['data' => [
            'number of all markets users' => $number_of_subscribed_users + $number_of_unSubscribed_users,
            'number of Subscribed Users' => $number_of_subscribed_users,
            'number of UnSubscribed users' => $number_of_unSubscribed_users,
        ]], 200);
    }

    /**
     * count the subscriptions and calculate the profit from the subscriptions (60% AND 40%) (BETWEEN TOW DATES)
     * @param Request $request 
     */
    public function getMarketSubscriptionsStatistics(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
        // $subscriptions = Subsicribtion::whereBetween('created_at', [$start_date, $end_date])->get();
        //$subcicribtions_count = count($subscribtion); 
        //$total_profit = $subscriptions * 100;
        //$_60_percetn_of_total_profit = $total_profit * 60 / 100;
        //$_40_percent_of_total_profit = $total_profit * 40 / 100;
        return response()->json([
            //'number_of_subscriptions'=>$subcicribtions_count,
            //'total_profit'=>$total_profit,
            //60%_of_the_total' => $_60_percetn_of_total_profit,
            //'40%_of_the_total' => $_40_percent_of_total_profit,

        ], 200);
    }

    /**
     * Show the top three requested markets and top three orderes suppliers
     * @return JsonResponse
     */
    public function getUsersWithBillsStatistics()
    {
        $this->authorize('viewAny', User::class);

        $top_three_markets_with_orders = Market::withCount('bills')->orderBy('bills_count', 'desc')->take(3)->get();
        $top_three_suppliers_submitting_orders = Supplier::withCount('bills')->orderBy('bills_count', 'desc')->take(3)->get();
        $top_three_suppliers_submitting_orders->makeHidden('min_bill_price');


        return response()->json(['data' => [
            'top_three_requested_market_for_orders' => $top_three_markets_with_orders,
            'top_three_orderes_supplier' => $top_three_suppliers_submitting_orders,
        ]], 200);
    }
}
