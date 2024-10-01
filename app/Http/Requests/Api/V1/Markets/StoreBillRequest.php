<?php

namespace App\Http\Requests\Api\V1\Markets;

use App\Rules\Bills\ProductExistsForSupplier;
use App\Rules\UniqueProductsPerSupplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


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
            'bills' => ['array','required'],
            'bills.*.supplier_id' => ['required','exists:suppliers,id'],
            'bills.*.coupon_code' => ['string'],
            'bills.*.payment_method_id' => ['required','exists:payment_methods,id'],
            'bills.*.products' => ['array','required'],
            'bills.*.products.*.id' => ['required', 'exists:products,id', new UniqueProductsPerSupplier],
            'bills.*.products.*.quantity' => ['required','integer','min:1'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        abort(response()->json([
            'message' => $error
        ], 422));
    }
}
