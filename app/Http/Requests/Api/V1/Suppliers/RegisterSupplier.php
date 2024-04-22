<?php

namespace App\Http\Requests\Api\V1\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class RegisterSupplier extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:suppliers,phone_number',
            'password' => 'required|string|min:6',
            'store_name' => 'required|string|max:255',
            'supplier_category_id' => 'required|integer|exists:supplier_categories,id',
            'min_bill_price' => 'required|numeric',
            'min_selling_quantity' => 'required|integer',
            'delivery_duration' => 'required|string|max:255',

        ];
    }
}
