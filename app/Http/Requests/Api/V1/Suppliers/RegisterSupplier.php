<?php

namespace App\Http\Requests\Api\V1\Suppliers;

use App\Rules\EgyptPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'deviceToken'=>'nullable',
            'last_name' => 'required|string',
            'phone_number' => ['required','string','unique:suppliers,phone_number',new EgyptPhoneNumber],
            'password' => 'required|string|min:6',
            'store_name' => 'required|string',
            'supplier_category_id' => 'required|integer|exists:supplier_categories,id',
            'min_selling_quantity' => 'required|integer',
            'location_details' => 'required|string',
            'city_id' => 'required|integer|exists:cities,id',
            'image' => ['required','image'],
            'to_sites' => 'required|array',
            'to_sites.*.min_bill_price' => 'required|numeric',
            'details'=>'nullable',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $Error = $errors->all();

        throw new HttpResponseException(response()->json([
            'message' => $Error
        ], 422));
    }

}
