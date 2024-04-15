<?php

namespace App\Http\Requests\Api\V1\Markets;

use App\Rules\Bills\ProductExistsForSupplier;
use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bills' => ['array','present'],
            'bills.*.supplier_id' => ['required','exists:suppliers,id'],
            'bills.*.payement_method_id' => ['required','exists:payement_methods,id'],
            'bills.*.products' => ['array','present'],
            'bills.*.products.*.id' => ['required'],
            'bills.*.products.*.quantity' => ['required','integer','min:1'],
        ];
    }
}
