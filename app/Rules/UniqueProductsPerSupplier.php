<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueProductsPerSupplier implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $data = request()->all();

        $supplierProducts = [];

        foreach ($data['bills'] as $bill) {
            $supplierId = $bill['supplier_id'];

            foreach ($bill['products'] as $product) {
                $productId = $product['id'];

                if (isset($supplierProducts[$supplierId][$productId])) {
                    $fail('The product IDs for each supplier must be distinct.');
                    return;
                }

                $supplierProducts[$supplierId][$productId] = true;
            }
        }
    }
}

