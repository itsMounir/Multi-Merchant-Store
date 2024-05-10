<?php

namespace App\Policies\Web;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasrole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Productcategory $productcategory): bool
    {
        return $user->hasrole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasrole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Productcategory $productcategory): bool
    {
        return $user->hasrole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Productcategory $productcategory): bool
    {
        return $user->hasrole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Productcategory $productcategory): bool
    {
        return $user->hasrole(['admin', 'data_entry']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    /*   public function forceDelete(User $user, Productcategory $productcategory): bool
    {
        return $user->hasrole(['admin','data_entry']) ;
    }*/
}
