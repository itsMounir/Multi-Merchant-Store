<?php

namespace App\Rules\Bills;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ProductExistsForSupplier implements ValidationRule
{
    private $supplierId;

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate($attribute, $value, Closure $fail) :void
    {
        if (!DB::table('product_supplier')
            ->where('product_id', $value)
            ->where('supplier_id', $this->supplierId)
            ->exists()
        ) {
            $fail("The selected product does not exist for the specified supplier.");
        }
    }
}
