<?php

namespace App\Http\Requests\Api\V1\Users;

use App\Models\Supplier;
use App\Rules\EgyptPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class SupplierProfileRequest extends FormRequest
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
        $user = $this->route('id');
        
        return [
            'first_name' => ['string', 'required'],
            'middle_name' => ['string', 'required'],
            'last_name' => ['string', 'required'],
            'phone_number' => ['string', 'unique:suppliers,phone_number,' . $user . ',id', 'required',new EgyptPhoneNumber],
            'store_name' => ['string', 'unique:suppliers,store_name,' . $user . ',id', 'required'],
            'supplier_category_id' => ['required'],
            'delivery_duration'=>['nullable'],
            //'min_bill_price' => ['required'],
            'min_selling_quantity' => ['required'],
        ];
    }
}
