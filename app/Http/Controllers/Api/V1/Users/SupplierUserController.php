<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierUserController extends Controller
{
    /**
     * GET SUPPLIER USERS BASED ON STATUS
     * @param Request $request
     * @return JsonResponse
     */
    public function supplierUsers(Request  $request)
    {
        $status = $request->query('status');
        $query = Supplier::query();

        if ($status) {
            $query->where('status', $status);
        }

        $marketUsers = $query->orderBy('first_name', 'asc')->get();
        return response()->json(['market users' => $marketUsers]);
    }

    /**
     * TO ACTIVATE SUPPLIER USER
     * @param ID $id
     * @return JsonResponse 
     */
    public function activateSupplierUser($id)
    {
        $user = Supplier::find($id);
        if ($user) {
            $user->status = 'نشط';
            $user->save();
            return response()->json(['message' => 'User has been activated successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    /**
     * TO DEACTIVATE SUPPLIER USER
     * @param ID $id
     * @return JsonResponse
     */
    public function deactivateSupplierUser($id)
    {
        $user = Supplier::find($id);
        if ($user) {
            $user->status = 'غير نشط';
            $user->save();
            return response()->json(['message' => 'User has been deactivated successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    /**
     * TO BAN SUPPLIER USER
     * @param ID $id
     * @return JsonResponse
     */
    public function banSupplierUser($id)
    {
        $user = Supplier::find($id);
        if ($user) {
            $user->status = 'محظور';
            $user->save();
            return response()->json(['message' => 'User has been banned successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
