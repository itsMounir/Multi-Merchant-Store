<?php

namespace App\Http\Requests\Api\V1\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class AddDiscountRequest extends FormRequest
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
            'discount' => 'required|array',
            'discount.*.starting_date' => 'required|date',
            'discount.*.expiring_date' => 'required|date|after_or_equal:discount.*.starting_date',
            'discount.*.min_price' => 'required|numeric|min:0',
            'discount.*.discount_price' => 'required|numeric|min:0|lt:offers.*.min_price',
        ];
    }
}
