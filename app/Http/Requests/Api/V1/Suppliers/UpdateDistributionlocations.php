<?php

namespace App\Http\Requests\Api\V1\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistributionlocations extends FormRequest
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
            'to_sites_id' => 'required|array',
            //'to_sites_id.*.id' => 'required|exists:cities,id',
            //'to_sites_id.*.min_bill_price' => 'required|numeric',
        ];
    }
}
