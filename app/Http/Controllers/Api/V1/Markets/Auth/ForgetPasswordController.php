<?php

namespace App\Http\Controllers\Api\V1\Markets\Auth;

use App\Http\Controllers\Controller;
use App\Models\{
    Code,
    Market
};
use App\Notifications\verfication_code;
use App\Traits\{
    createVerificationCode,
    ExpierCode
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ForgetPasswordController extends Controller
{
    use createVerificationCode, ExpierCode;
    public function forgetPassword(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required',
            'name' => 'required',
        ]);

        $market = Market::where('phone_number', $validated['phone'])->firstOrFail();
        $verificationCode = $this->getOrCreateVerificationCode($validated['phone'], $validated['name']);

        Notification::route('mail', 'almowafratys09@gmail.com')
            ->notify(new verfication_code($market, $verificationCode));

        return $this->sudResponse('.لقد تم إرسال طلبك إلى المشرف');
    }


    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required',
        ]);

        $code = Code::where('verification_code', $request->verification_code)
            ->first();

        if (!$code) {
            return $this->sudResponse('.الرمز غير صحيح', 400);
        }

        if ($this->isCodeExpired($code)) {
            $code->delete();
            return $this->sudResponse('.رمز التحقق لم يعد صالحا', 400);
        }

        return $this->sudResponse('تم تأكيد الرمز');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        $market = Market::where('phone_number', $request->phone_number)->first();
        $market->password = bcrypt($request->password);
        $market->save();

        Code::where('phone', $request->phone_number)->delete();

        return $this->sudResponse('.تم إعادة تعيين كلمة المرور بنجاح');
    }


}
