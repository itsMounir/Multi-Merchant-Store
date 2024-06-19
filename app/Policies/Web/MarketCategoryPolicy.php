<?php

namespace App\Policies\Web;

use App\Models\User;
use App\Models\MarketCategory;
use Illuminate\Auth\Access\Response;

class MarketCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
        //return $user->hasRole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, marketCategory $marketCategory): bool
    {
        return true;
        //return $user->hasRole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, marketCategory $marketCategory): bool
    {
        return $user->hasRole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, marketCategory $marketCategory): bool
    {
        return $user->hasRole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, marketCategory $marketCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, marketCategory $marketCategory): bool
    {
        return false;
    }
}
