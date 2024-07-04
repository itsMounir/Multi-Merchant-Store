<?php

namespace App\Policies\Web;

use App\Models\User;
use App\Models\Bill;
use App\Models\Product;
use App\Models\Market;
use App\Models\Supplier;
use App\Models\Offer;

class StatisticPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        
        return $user->hasRole(['admin']);
    }

    
}
