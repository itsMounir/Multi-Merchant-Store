<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketUserController extends Controller
{
    /**
     * GET MARKET USERS BASED ON STATUS
     * @param Request $request
     * @return JsonResponse
     */
    public function marketUsers(Request  $request)
    {
        $status = $request->query('status');
        $query = Market::with('category:id,name');

        if ($status) {
            $query->where('status', $status);
        }

        $marketUsers = $query->orderBy('first_name', 'asc')->get();
        return response()->json(['Supplier users' => $marketUsers]);
    }
    /**
     * TO ACTIVATE MARKET USER
     * @param ID $id
     * @return JsonResponse
     */
    public function activateMarketUser($id)
    {
        $user = Market::find($id);
        if ($user) {
            $user->status = 'نشط';
            $user->save();
            return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    /**
     * TO DEACTIVATE MARKET USER
     * @param ID $id
     * @return JsonResponse
     */
    public function deactivateMarketUser($id)
    {
        $user = Market::find($id);
        if ($user) {
            $user->status = 'غير نشط';
            $user->save();
            return response()->json(['message' => 'User has been deactivated successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    
    /**
     * TO BAN MARKET USER
     * @param ID $id
     * @return JsonResponse
     */
    public function banMarketUser($id)
    {
        $user = Market::find($id);
        if ($user) {
            $user->status = 'محظور';
            $user->save();
            return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
