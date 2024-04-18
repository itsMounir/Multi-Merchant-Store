<?php

namespace App\Policies\Markets;

use App\Models\{
    Bill,
    Market
};
use Illuminate\Auth\Access\Response;

class BillPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Market $market): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Market $market, Bill $bill): bool
    {
        return ($market->id == $bill->market_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Market $market): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Market $market, Bill $bill): bool
    {
        return ($bill->status == 'انتظار') && ($market->id == $bill->market_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Market $market, Bill $bill): bool
    {
        return ($bill->status == 'انتظار') && ($market->id == $bill->market_id);
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(Market $market, User $model): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(Market $market, User $model): bool
    // {
    //     //
    // }
}
