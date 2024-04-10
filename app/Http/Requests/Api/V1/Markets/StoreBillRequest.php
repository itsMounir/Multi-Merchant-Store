<?php

namespace App\Http\Requests\Api\V1\Markets;

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
            'supplier_id' => ['required','exists:suppliers,id'],
            'payement_method_id' => ['required','exists:payement_methods,id'],
            'cart' => ['array','present'],
            'cart.id.*' => ['required','exists:products,id'],
            'cart.quantity.*' => ['required','integer'],
        ];
    }
}
