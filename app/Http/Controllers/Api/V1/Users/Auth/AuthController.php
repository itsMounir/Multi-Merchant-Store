<?php

namespace App\Http\Controllers\Api\V1\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\Auth\LoginRequest;
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

        $token = $user->createToken('access_token', ['role:user'])->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully.',
            'access_token' => $token,
        ]);
    }

    public function destroy()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
