<?php

namespace App\Http\Controllers\api\v1\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Market;
use App\Models\Product;
use App\Models\Bill;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{

    public function getBillsWithAdditionalCost()
    {

        $bills = Bill::with(['supplier', 'market'])->where('has_additional_cost', 1)->latest()->get();

        $billsCount = count($bills);
        $totalCost = 0;
        $totalAdditionalCost = 0;

        foreach ($bills as $bill) {
            $totalAdditionalCost += $bill->additional_price;
            $totalCost += $bill->total_price;
        }
        $totalProfit = $totalAdditionalCost - $totalCost;
        $_60_percetn_of_totalProfit = $totalProfit * 60 / 100;
        $_40_percent_of_totalProfit = $totalProfit * 40 / 100;

        return [
            'bills' => $bills,
            'bills count' => $billsCount,
            'total price with additional cost' => $totalAdditionalCost,
            'total price without additional cost' => $totalCost,
            'total additional cost ' => $totalProfit,
            '60% of the total' => $_60_percetn_of_totalProfit,
            '405 of the total' => $_40_percent_of_totalProfit,
        ];
    }
}
