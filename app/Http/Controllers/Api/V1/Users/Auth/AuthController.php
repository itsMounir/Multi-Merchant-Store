<?php

namespace App\Http\Controllers\Api\V1\Users\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\Auth\LoginRequest;
use App\Models\User;
use App\Notifications\EmployeePasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    public function create(LoginRequest $request)
    {
        $credentials = $request->only('phone_number', 'password');

        if (!Auth::guard('web')->attempt($credentials)) {

            return response()->json(['message' => 'يرجى التحقق من كلمة المرور أو رقم الهاتف'], 401);
        }
        $user = User::find(Auth::guard('web')->user()->id);
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
        $user = User::find(Auth::user()->id);
        //Auth::user()->currentAccessToken()->delete();
        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
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
        return response([
            'message' => '.تم إنشاء الرمز بنجاح',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * Reset a new password
     * @param Request $request
     * @return JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|exists:users,phone_number',
                'email' => 'required|exists:users,email',
            ]);

            $phone_number = $request->phone_number;
            $email = $request->email;
            $user = User::where('phone_number', $phone_number)->where('email', $email)->first();
            if (!$user) {
                return response()->json(['message' => 'الرقم و الحساب غير متطابقين'], 422);
            }
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $new_password = substr(str_shuffle($characters), 0, 10);
            $user->password = bcrypt($new_password);
            $user->save();
            Notification::route('mail', $user->email)
                ->notify(new EmployeePasswordReset($user, $new_password));
            return response()->json(['message' => 'تم تغيير كلمة مرور حسابك, من فضلك راجع بريدك الإلكتروني'], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    public function profile()
    {
        return Auth::user();
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);
        $user = User::find(Auth::user()->id);
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'كلمة مرور الحالية غير صحيحة'], 422);
        }
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'تم تغيير كلمة مرور حسابك'], 200);
    }
}
