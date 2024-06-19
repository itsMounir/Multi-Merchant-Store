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
     * Display listing of markets (filtered on category and status)
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Market::class);

        $category = $request->query('category');
        $status = $request->query('status');

        $query = Market::query();

        if ($category) {
            $query->where('market_category_id', $category);
        }
        if (!is_null($status)) {
            $query->where('status', $status);
        }
        $markets = $query->orderBy('first_name', 'asc')->paginate(20, ['*'], 'p');

        return response()->json(['market users' => $markets]);
    }

    /**
     * Display User info.
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
     * Display user info with his outcoming Bills
     * @param string $id
     * @return JsonResponse
     */
    public function userWithBills(String $id)
    {
        $market = Market::with(['bills.supplier'])->findOrFail($id);
        $this->authorize('view', $market);

        return response()->json(['user' => $market]);
    }

    /**
     * Display list of market that match the inserted name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $this->authorize('viewAny',Market::class);
        try {
            $name = $request->query('name');
            $supplier = Market::where('store_name', 'like', '%' . $name . '%')->orderBy('first_name', 'asc')->paginate(20, ['*'], 'p');
            return response()->json($supplier, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * update supplier info
     * @param MarketProfileRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(MarketProfileRequest $request, String $id)
    {
        $market = Market::findOrFail($id);
        $this->authorize('update', $market);
        $market->update($request->all());
        return response()->json(['message' => 'User has been updated successfully', 'user' => $market], 200);
    }
    

    /**
     * Activate market account
     * @param string $id
     * @return JsonResponse
     */
    public function activate(String $id)
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
     * Ban market account
     * @param string $id
     * @return JsonResponse
     */
    public function ban(String $id)
    {
        $market = Market::findOrFail($id);
        $this->authorize('update', $market);

        try {
            if ($market->status === "محظور")
                return response()->json(['message' => 'User is alredy banned']);
            $market->status = "محظور";
            $market->tokens()->delete();
            $market->save();


            return response()->json(['message' => 'User has been banned successfully', 'user' => $market], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
