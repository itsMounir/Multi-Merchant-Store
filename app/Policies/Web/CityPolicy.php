<?php

namespace App\Policies\web;

use App\Models\City;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CityPolicy
{
   /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasrole(['data_entry','admin']) ;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasrole(['data_entry','admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasrole(['data_entry','admin']);

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, City $City): bool
    {
        return $user->hasrole(['data_entry','admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, City $City): bool
    {
        return $user->hasrole(['data_entry','admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, City $City): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, City $City): bool
    {
        return false;
    }
}
