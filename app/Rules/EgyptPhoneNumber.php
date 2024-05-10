<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EgyptPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\+201\d{9}$/', $value)) {
            $fail(".يجب أن يكون رقم الهاتف رقم هاتف مصري صالح (على سبيل المثال، 201111111111+)");
        }
    }
}
