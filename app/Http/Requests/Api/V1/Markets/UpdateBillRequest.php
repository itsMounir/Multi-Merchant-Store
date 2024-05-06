<?php

namespace App\Http\Requests\Api\V1\Markets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBillRequest extends FormRequest
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
            'supplier_id' => ['exists:suppliers,id'],
            'payment_method_id' => ['exists:payment_methods,id'],
            'products' => ['array'],
            'products.*.quantity' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'cart.*.id.required'
            => 'we don\'t have this product in the store .',
        ];
    }
}
