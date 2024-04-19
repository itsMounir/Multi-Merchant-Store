<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketUserController extends Controller
{
    /**
     * To get user info with his outcoming Bills 
     * @param string $id
     * @return JsonResponse
     */
    public function userWithBills($id)
    {
        $user = Market::with('bills')->findOrFail($id);
        return response()->json(['user' => $user]);
    }

    /**
     * GET MARKET USERS BASED ON STATUS
     * @param Request $request
     * @return JsonResponse
     */
    public function marketUsers(Request  $request)
    {
        $category = $request->query('category');
        $marketUsers = Market::where('market_category_id', $category)->orderBy('first_name', 'asc')->get();
        return response()->json(['Supplier users' => $marketUsers]);
    }
    /**
     * TO ACTIVATE MARKET USER
     * @param string $id
     * @return JsonResponse
     */
    public function activateMarketUser($id)
    {
        $user = Market::findOrFail($id);
        if ($user->status == 'نشط')
            return response()->json(['message' => 'User is alredy Activated']);
        $user->status = 'نشط';
        $user->save();
        return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
    }
    
    /**
     * TO DEACTIVATE MARKET USER
     * @param string $id
     * @return JsonResponse
     */
   /* public function deactivateMarketUser($id)
    {
        $user = Market::findOrFail($id);
        if ($user->status == 'غير نشط')
            return response()->json(['message' => 'User is alredy Deactivated']);
        $user->status = 'غير نشط';
        $user->save();
        return response()->json(['message' => 'User has been deactivated successfully', 'user' => $user], 200);
    }*/

    /**
     * TO BAN MARKET USER
     * @param string $id
     * @return JsonResponse
     */
    public function banMarketUser($id)
    {
        $user = Market::findOrFail($id);
        if ($user->status == 'محظور')
            return response()->json(['message' => 'User is alredy banned']);
        $user->status = 'محظور';
        $user->save();
        return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
    }
}
