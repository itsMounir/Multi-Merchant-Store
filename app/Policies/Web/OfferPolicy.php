<?php

namespace App\Policies\Web;

use App\Models\User;
use App\Models\Offer;
use Illuminate\Auth\Access\Response;

class OfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
        //return $user->hasRole(['admin', 'data_entry', 'supervisor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Offer $Offer): bool
    {
        return true;
        //return $user->hasRole(['admin', 'data_entry', 'supervisor']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'data_entry', 'supervisor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Offer $Offer): bool
    {
        return $user->hasRole(['admin', 'data_entry', 'supervisor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Offer $Offer): bool
    {
        return $user->hasRole(['admin', 'data_entry', 'supervisor']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Offer $Offer): bool
    {
        return $user->hasRole(['admin', 'data_entry', 'supervisor']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Offer $Offer): bool
    {
        return $user->hasRole(['admin', 'data_entry', 'supervisor']);
    }
}
