<?php

namespace App\Http\Requests\Api\V1\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        'product_id' => 'required',
        'price' => 'required',
        'max_selling_quantity' => 'required|numeric|min:1',
       'quantity' => 'required|numeric|min:1',
        'has_offer' => 'nullable',
        'offer_price' => 'required_if:has_offer,1|nullable',
        'max_offer_quantity' => 'required_if:has_offer,1|nullable|numeric',
        'offer_expires_at' => 'required_if:has_offer,1|nullable|date'

        ];


    }
}
