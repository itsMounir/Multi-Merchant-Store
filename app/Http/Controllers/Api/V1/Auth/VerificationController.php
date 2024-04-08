<?php

namespace App\Http\Controllers\Api\V1\Auth;
use App\Models\User;
use App\Traits\Is_Expire_Code;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Auth\VerificationRequest;


class VerificationController extends Controller
{
    use Is_Expire_Code;

    public function check(VerificationRequest $request){

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ],401);
    }

    if ($this->isCodeExpired($user)) {
        $user->delete();
        return response()->json([
            'message' => 'Code is expired'
        ],401);
    }

    if ($user->code != $request->code) {

        return response()->json([
            'message' => 'Code not correct,please re-enter agian'
        ],401);
    }

    return response()->json([
        'message' => 'User register successfully'
    ],201);
    }

}
