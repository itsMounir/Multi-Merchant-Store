<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\MarketProfileRequest;
use App\Models\Market;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketUserController extends Controller
{
    /**
     * Display User Profile.
     * @param string $id
     * @return JsonResponse
     */
    public function show(String $id)
    {
        $market = Market::findOrFail($id);
        $this->authorize('view', $market);

        return response()->json(['user' => $market], 200);
    }
    /**
     * To get user info with his outcoming Bills
     * @param string $id
     * @return JsonResponse
     */
    public function userWithBills(String $id)
    {
        $market = Market::with('bills.products')->findOrFail($id);
        $this->authorize('view', $market);

        return response()->json(['user' => $market]);
    }

    /**
     * To change supplier profile
     * @param MarketProfileRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(MarketProfileRequest $request, String $id)
    {
        $market = Market::findOrFail($id);
        $this->authorize('update', $market);

        $market->update($request->all());
        $market = Market::findOrFail($id);
        return response()->json(['message' => 'User has been updated successfully', 'user' => $market], 200);
    }

    /**
     * GET MARKET USERS BASED ON CATEGORY
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request  $request)
    {
        $this->authorize('viewAny', Market::class);

        $category = $request->query('category');

        if ($category)
            $markets = Market::where('market_category_id', $category)->orderBy('first_name', 'asc')->get();
        else {
            $markets = Market::get();
        }

        return response()->json(['Market users' => $markets]);
    }
    /**
     * TO ACTIVATE MARKET USER
     * @param string $id
     * @return JsonResponse
     */
    public function activateUser(String $id)
    {
        $market = Market::findOrFail($id);
        $this->authorize('update', $market);


        try {
            if ($market->status === 'نشط')
                return response()->json(['message' => 'User is alredy Activated']);
            $market->status = 'نشط';
            $market->save();

            return response()->json(['message' => 'User has been activated successfully', 'user' => $market], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * TO BAN MARKET USER
     * @param string $id
     * @return JsonResponse
     */
    public function banUser(String $id)
    {
        $market = Market::findOrFail($id);
        $this->authorize('update', $market);

        try {
            if ($market->status === "محظور")
                return response()->json(['message' => 'User is alredy banned']);
            $market->status = "محظور";
            $market->save();


            return response()->json(['message' => 'User has been banned successfully', 'user' => $market], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
