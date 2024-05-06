<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Users\CategoryRequest;
use App\Http\Requests\Api\V1\Users\MarketProfileRequest;
use App\Models\Market;
use App\Models\MarketCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketUserController extends Controller
{
    /**
     * Display User Profile.
     * @param string $id
     * @return JsonResponse
     */
    public function profile($id)
    {
        $market = Market::with('category:id,name')->findOrFail($id);
        return response()->json(['user' => $market], 200);
    }
    /**
     * To get user info with his outcoming Bills 
     * @param string $id
     * @return JsonResponse
     */
    public function userWithBills($id)
    {
        $user = Market::with(['bills.products', 'category:id,name'])->findOrFail($id);
        return response()->json(['user' => $user]);
    }

    /**
     * To change supplier profile
     * @param MarketProfileRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function profileEdit(MarketProfileRequest $request, $id)
    {
        $user = Market::findOrFail($id);
        $user->update($request->all());
        $user = Market::with('category:id,name')->findOrFail($id);
        return response()->json(['message' => 'User has been updated successfully', 'user' => $user], 200);
    }

    /**
     * GET MARKET USERS BASED ON STATUS
     * @param Request $request
     * @return JsonResponse
     */
    public function marketUsers(Request  $request)
    {
        $category = $request->query('category');

        if ($category)
            $marketUsers = Market::with('category:id,name')->where('market_category_id', $category)->orderBy('first_name', 'asc')->get();
        else {
            $marketUsers = Market::with('category:id,name')->all();
        }
        return response()->json(['Market users' => $marketUsers]);
    }
    /**
     * TO ACTIVATE MARKET USER
     * @param string $id
     * @return JsonResponse
     */
    public function activateMarketUser($id)
    {
        $user = Market::with('category:id,name')->findOrFail($id);
        if ($user->status == 'نشط')
            return response()->json(['message' => 'User is alredy Activated']);
        $user->status = 'نشط';
        $user->save();
        return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
    }

    /**
     * TO BAN MARKET USER
     * @param string $id
     * @return JsonResponse
     */
    public function banMarketUser($id)
    {
        $user = Market::with('category:id,name')->findOrFail($id);
        if ($user->status == 'محظور')
            return response()->json(['message' => 'User is alredy banned']);
        $user->status = 'محظور';
        $user->save();
        return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
    }


    /**
     * To get Markets categories
     * @return JsonResponse
     */
    public function getCategories()
    {
        $categories = MarketCategory::all();
        return response()->json($categories, 200);
    }
    /**
     * To create new category
     * @param string $id
     * @return JsonResponse
     */
    public function createCategory(CategoryRequest $request)
    {
        $category = MarketCategory::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * To Update category
     * @param CategoryRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function updateCategory(CategoryRequest $request, $id)
    {
        $category = MarketCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json($category, 200);
    }
    /**
     * To delete category
     * @param string $id
     * @return JsonResponse
     */
    public function destroyCategory($id)
    {
        $category = MarketCategory::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
