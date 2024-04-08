<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {

            return response()->json(['message' => 'your provided credentials cannot be verified.'], 401);
        }
        $user = Auth::user();

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully.',
            'access_token' => $token,
        ]);
    }

    public function destroy() {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
