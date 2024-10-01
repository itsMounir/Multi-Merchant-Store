<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    /**
     * Display list of coupons
     * @param Request $request user inputs for filtering
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query  = Coupon::query();
        $perPage = $request->input('per_page', 20);
        $filter = $this->filter($request, $query);
        $coupons = $filter->paginate($perPage, ['*'], 'p');
        return response()->json(['coupons' => $coupons], 200);
    }


    public function store(Request $request)
    {
        $query = $request->validate([
            'code' => 'required|string|min:6|max:6',
            'min_bill_limit' => 'required',
            'disscount_value' => 'required',
        ]);

        $coupon = Coupon::create($query);

        return response()->json(['coupon' => $coupon], 201);
    }

    public function active(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        if ($coupon->active != true) {
            $coupon->active = true;
            $coupon->save();
            return response()->json($coupon, 200);
        } else {
            return response()->json(['message' => 'this code is alredy activated'], 422);
        }
    }

    public function deactive(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        if ($coupon->active != false) {
            $coupon->active = false;
            $coupon->save();
            return response()->json($coupon, 200);
        } else {
            return response()->json(['message' => 'this code is alredy deactivated'], 422);
        }
    }

    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return response()->json(null, 204);
    }
}
