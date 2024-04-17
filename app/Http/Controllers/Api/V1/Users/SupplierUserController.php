<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierUserController extends Controller
{
    /**
     * To get user info with his incoming Bills 
     * @param ID $id
     * @return JsonResponse
     */
    public function userWithBills($id)
    {
        $user = Supplier::with('bills')->findOrFail($id);
        return response()->json(['user' => $user]);
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
     * @param ID $id
     * @return JsonResponse 
     */
    public function activateSupplierUser($id)
    {
        $user = Supplier::find($id);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);
        if ($user->status = 'نشط')
            return response()->json(['message' => 'User is alredy Activated....'], 200);
        $user->status = 'نشط';
        $user->save();
        return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
    }
    /**
     * TO DEACTIVATE SUPPLIER USER
     * @param ID $id
     * @return JsonResponse
     */
    public function deactivateSupplierUser($id)
    {
        $user = Supplier::find($id);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);
        if ($user->status = 'غير نشط')
            return response()->json(['message' => 'user already is Deactivated...'], 200);
        $user->status = 'غير نشط';
        $user->save();
        return response()->json(['message' => 'User has been deactivated successfully', 'user' => $user], 200);
    }
    /**
     * TO BAN SUPPLIER USER
     * @param ID $id
     * @return JsonResponse
     */
    public function banSupplierUser($id)
    {
        $user = Supplier::find($id);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);
        if ($user->status = 'محظور')
            return response()->json(['message' => 'User is alredy Banned...'], 200);
        $user->status = 'محظور';
        $user->save();
        return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
    }
}
