<?php

namespace App\Http\Controllers\Api\v1\Users;

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
    public function profile($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json(['user' => $supplier], 200);
    }

    /**
     * To get user info with his incoming Bills 
     * @param string $id
     * @return JsonResponse
     */
    public function userWithBills($id)
    {
        $user = Supplier::with('bills')->findOrFail($id);
        return response()->json(['user' => $user]);
    }
    /**
     * To change supplier profile
     * @param SupplierProfileRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function profileEdit(SupplierProfileRequest $request, $id)
    {
        $user = Supplier::findOrFail($id);
        $user->update($request->all());
        return response()->json(['message' => 'User has been updated successfully', 'user' => $user], 200);
    }

    /**
     * GET SUPPLIER USERS BASED ON STATUS
     * @param Request $request
     * @return JsonResponse
     */
    public function supplierUsers(Request  $request)
    {
        $category = $request->query('category');
        $supplierUsers = Supplier::query()->where('supplier_category_id', $category)->orderBy('first_name', 'asc')->get();
        return response()->json(['supplier users' => $supplierUsers]);
    }

    /**
     * TO ACTIVATE SUPPLIER USER
     * @param string $id
     * @return JsonResponse 
     */
    public function activateSupplierUser($id)
    {
        $user = Supplier::find($id);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);
        if ($user->status == 'نشط')
            return response()->json(['message' => 'User is alredy Activated....'], 200);
        $user->status = 'نشط';
        $user->save();
        return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
    }

    /**
     * TO BAN SUPPLIER USER
     * @param string $id
     * @return JsonResponse
     */
    public function banSupplierUser($id)
    {
        $user = Supplier::find($id);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);
        if ($user->status == 'محظور')
            return response()->json(['message' => 'User is alredy Banned...'], 200);
        $user->status = 'محظور';
        $user->save();
        return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
    }
}
