<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\SupplierProfileRequest;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierUserController extends Controller
{
    /**
     * Display User Profile.
     * @param string $id
     * @return JsonResponse
     */
    public function show($id)
    {

        $supplier = Supplier::with(['category:id,type', 'city:id,name', 'distributionLocations'])->findOrFail($id);
        $this->authorize('view', $supplier);

        $supplier->category_name = $supplier->category->type;
        $supplier->city_name = $supplier->city->name;

        return response()->json(['user' => $supplier], 200);
    }

    /**
     * To get user info with his incoming Bills
     * @param string $id
     * @return JsonResponse
     */
    public function userWithBills($id)
    {
        $user = Supplier::with(['bills.market'])->findOrFail($id);
        $this->authorize('view', $user);

        $user->category_name = $user->category->type;
        $user->city_name = $user->city->name;
        return response()->json(['user' => $user]);
    }
    /**
     * To change supplier profile
     * @param SupplierProfileRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(SupplierProfileRequest $request, $id)
    {
        $user = Supplier::findOrFail($id);
        $this->authorize('update', $user);
        $user->update($request->all());
        $user = Supplier::with('category:id,type', 'city:id,name', 'distributionLocations')->findOrFail($id);

        $user->category_name = $user->category->type;
        $user->city_name = $user->city->name;

        return response()->json(['message' => 'User has been updated successfully', 'user' => $user], 200);
    }

    /**
     * GET SUPPLIER USERS BASED ON category
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request  $request)
    {

        $this->authorize('viewAny', Supplier::class);

        $category = $request->query('category');
        if ($category) {
            $suppliers = Supplier::query()->with('category:id,type', 'city:id,name', 'distributionLocations')->where('supplier_category_id', $category)->orderBy('first_name', 'asc')->get();
        } else {
            $suppliers = Supplier::with('category:id,type', 'city:id,name')->get();
        }
        foreach ($suppliers as $supplier) {
            $supplier->category_name = $supplier->category->type;
            $supplier->city_name = $supplier->city->name;
        }
        return response()->json(['supplier users' => $suppliers]);
    }

    /**
     * TO ACTIVATE SUPPLIER USER
     * @param string $id
     * @return JsonResponse
     */
    public function activate($id)
    {
        $user = Supplier::with('category:id,type', 'city:id,name', 'distributionLocations')->findOrFail($id);
        $this->authorize('update', $user);
        try {
            if (!$user)
                return response()->json(['message' => 'User not found'], 404);
            if ($user->status == 'نشط')
                return response()->json(['message' => 'User is alredy Activated....'], 200);
            $user->status = 'نشط';
            $user->save();

            $user->category_name = $user->category->type;
            $user->city_name = $user->city->name;
            return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * TO BAN SUPPLIER USER
     * @param string $id
     * @return JsonResponse
     */
    public function ban($id)
    {
        $user = Supplier::with('category:id,type', 'city:id,name', 'distributionLocations')->findOrFail($id);
        $this->authorize('update', $user);

        try {
            if ($user->status == 'محظور')
                return response()->json(['message' => 'User is alredy Banned...'], 200);
            $user->status = 'محظور';
            $user->tokens()->delete();
            $user->save();

            $user->category_name = $user->category->type;
            $user->city_name = $user->city->name;

            return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
