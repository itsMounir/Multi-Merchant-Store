<?php

namespace App\Http\Controllers\Api\V1\Users\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\Auth\LoginRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create(LoginRequest $request)
    {
        $credentials = $request->only('phone_number', 'password');

        if (!Auth::guard('web')->attempt($credentials)) {

            return response()->json(['message' => 'your provided credentials cannot be verified.'], 401);
        }
        $user = Auth::guard('web')->user();
        /*
        $token = $user->createToken('access_token', ['role:user'])->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully.',
            'access_token' => $token,
        ]);*/
        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:user'],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'user' => $user,
        ]);
    }

    public function destroy()
    {
        //Auth::user()->currentAccessToken()->delete();
         Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
