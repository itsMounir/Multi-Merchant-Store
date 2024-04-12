<?php

namespace App\Policies\Markets;

use App\Models\Bill;
use App\Models\Market;
use Illuminate\Auth\Access\Response;

class BillPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(Market $user, Bill $bill): bool
    {
        return ($user->id == $bill->market_id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Market $user, Bill $bill): bool
    {
        return ($bill->status == 'غير مدفوع') && ($user->id == $bill->market_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Market $user, Bill $bill): bool
    {
        return ($bill->status == 'غير مدفوع') && ($user->id == $bill->market_id);
    }
}
