<?php

namespace App\Http\Controllers\Api\V1\Suppliers\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Suppliers\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(LoginSupplier $request) {
        $credentials = $request->only('phone_number', 'password');

        if (! Auth::guard('supplier')->attempt($credentials)) {

            return response()->json(['message' => 'لا يمكن التحقق من البيانات التي قدمتها'], 401);
        }
        $supplier = Auth::guard('supplier')->user();

        $accessToken = $supplier->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:supplier'],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $supplier->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'supplier'=>$supplier,
        ]);
    }

    public function destroy() {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function refreshToken(Request $request)
    {
        $supplier = $request->user();
        $supplier->tokens()->delete();
        $accessToken = $supplier->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:supplier'],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $supplier->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );
        return response([
            'message' => '.تم إنشاء الرمز بنجاح',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'supplier' => $supplier,
        ]);
    }

}
