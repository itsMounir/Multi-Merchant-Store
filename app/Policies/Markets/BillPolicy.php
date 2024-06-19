<?php

namespace App\Policies\Markets;

use App\Models\{
    Bill,
    Market,
    User
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
        return ($bill->isUpdatable()) && ($market->id == $bill->market_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Market $market, Bill $bill): bool
    {
        return ($bill->isUpdatable()) && ($market->id == $bill->market_id);
    }


    public function webViewAny(User $user): bool
    {
        return true;
        //return $user->hasrole(['admin', 'moderator']);
    }

    public function webView(User $user): bool
    {
        return true;
        //return $user->hasrole(['admin', 'moderator']);
    }


    public function webUpdate(User $user, Bill $bill): bool
    {
        return $user->hasrole(['admin', 'moderator']);
    }
}
