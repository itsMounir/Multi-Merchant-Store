<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Bill;

use Illuminate\Http\Request;

class BillsController extends Controller
{
    public function billDecision(Bill $bill, $status)
    {
        if ($status == 'تأكيد') {
            $bill->status = 'مؤكدة';
        return $this->sudResponse('order has been accepted and sent to the Market',200);
        } elseif ($status == 'إلغاء') {
            $bill->status = 'ملغي';
        return $this->sudResponse('order has been declain',200);
        }
    }
}
