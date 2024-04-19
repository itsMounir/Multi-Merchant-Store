<?php

namespace App\Http\Requests\Api\V1\Users;

use Illuminate\Foundation\Http\FormRequest;

class SupplierProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string','required'],
            'middle_name' =>['string','required'] ,
            'last_name' => ['string','required'],
            'phone_number' => ['string','required'],
            'supplier_category_id' =>['required'] ,
            'min_bill_price' => ['required'],
            'min_selling_quantity' => ['required'],
        ];
    }
}
