<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Market;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{

    public function getPublicStatistics(Request $request){


    }

    public function getBillStatistics(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
        $bills = Bill::whereBetween('created_at', [$start_date, $end_date])->get();
        $bills_count = count($bills);
        $bills_with_additional_cost = $bills->where('has_additional_cost', 1);
        $bills_without_additional_cost = $bills->where('has_additional_cost', 0);
        $bills_with_additional_cost_count = count($bills_with_additional_cost);
        $bills_without_additional_cost_count = count($bills_without_additional_cost);

        $total_cost = 0;
        $total_additional_cost = 0;

        foreach ($bills as $bill) {
            $total_additional_cost += $bill->additional_price;
            $total_cost += $bill->total_price;
        }
        $total_profit = $total_additional_cost - $total_cost;
        $_60_percetn_of_total_profit = $total_profit * 60 / 100;
        $_40_percent_of_total_profit = $total_profit * 40 / 100;

        return response()->json(['data' => [
            'bills count' => $bills_count,
            'bills with additional cost ' => $bills_with_additional_cost,
            'bills with additional cost count' => $bills_with_additional_cost_count,
            'total price with additional cost' => $total_additional_cost,
            'bills without additional cost ' => $bills_without_additional_cost,
            'bills without additional cost count' => $bills_without_additional_cost_count,
            'total price without additional cost' => $total_cost,
            'total additional cost ' => $total_profit,
            '60% of the total' => $_60_percetn_of_total_profit,
            '405 of the total' => $_40_percent_of_total_profit,
        ]], 200);
    }

    public function getUsersStatistics()
    {
        // $start_date = $request->query('start_date');
        // $end_date = $request->query('end_date');
        $subscribed_users = Market::with('city', 'category')->where('is_subscribed', 1)->latest()->get();
        $unSubscribed_users = Market::with('city', 'category')->where('is_subscribed', 0)->latest()->get();
        $number_of_subscribed_users = count($subscribed_users);
        $number_of_unSubscribed_users = count($unSubscribed_users);
        return response()->json(['data' => [
            'number of all markets users' => $number_of_subscribed_users + $number_of_unSubscribed_users,
            'number of Subscribed Users' => $number_of_subscribed_users,
            'Subscribed Users' => $subscribed_users,
            'number of UnSubscribed users' => $number_of_unSubscribed_users,
            'UnSubscribed Users' => $unSubscribed_users,
        ]], 200);
    }

    public function getUsersWithBillsStatistics()
    {

        $top_requested_markets_for_orders = Market::withCount('bills')->orderBy('bills_count', 'desc')->get();
        $top_orderes_suppliers = Supplier::withCount('bills')->orderBy('bills_count', 'desc')->get();

        return response()->json(['data' => [
            'top_requested_market_for_orders' => $top_requested_markets_for_orders,
            'top_orderes_supplier' => $top_orderes_suppliers,
        ]], 200);
    }
}
