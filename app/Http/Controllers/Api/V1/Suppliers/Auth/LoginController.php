<?php

namespace App\Http\Controllers\Api\V1\Suppliers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(Request $request) {
        $credentials = $request->only('phone_number', 'password');

        if (! Auth::guard('supplier')->attempt($credentials)) {

            return response()->json(['message' => 'your provided credentials cannot be verified.'], 401);
        }
        $user = Auth::guard('supplier')->user();
        //dd($user);
        $token = $user->createToken('access_token', ['role:supplier'])->plainTextToken;

        return response()->json([
            'message' => 'Supplier logged in successfully.',
            'access_token' => $token,
        ]);
    }

    public function destroy() {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
