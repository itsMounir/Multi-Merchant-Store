<?php

namespace App\Http\Controllers\Api\V1\Markets\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Markets\Auth\LoginRequest;
use App\Traits\{
    createVerificationCode,
    ExpierCode
};
use Illuminate\Http\Request;
use Illuminate\Support\{
    Facades\Auth,
    Carbon
};
use App\Enums\TokenAbility;

class LoginController extends Controller
{
    use createVerificationCode, ExpierCode;
    public function create(LoginRequest $request)
    {
        $credentials = $request->only('phone_number', 'password');

        if (!Auth::guard('market')->attempt($credentials)) {

            return response()->json(['message' => '.رقم الهاتف أو كلمة المرور خاطئة'], 401);
        }
        $market = Auth::guard('market')->user();
        if ($request->has('deviceToken')) {
            $market->update(['deviceToken' => $request->deviceToken]);
        }

        $accessToken = $market->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:market'],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $market->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'market' => $market,
        ]);
    }

    public function destroy()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => '.تم تسجيل الخروج بنجاح']);
    }

    public function refreshToken(Request $request)
    {
        $market = $request->user();
        $market->tokens()->delete();
        $accessToken = $market->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:market'],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $market->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );
        return response([
            'message' => '.تم إنشاء الرمز بنجاح',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'market' => $market,
        ]);
    }

}



