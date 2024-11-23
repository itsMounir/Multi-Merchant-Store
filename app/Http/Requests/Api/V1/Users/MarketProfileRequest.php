<?php

namespace App\Http\Requests\Api\V1\Users;

use App\Models\Market;
use App\Rules\EgyptPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MarketProfileRequest extends FormRequest
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
            'phone_number' => ['string', 'unique:suppliers,phone_number,' . $user . ',id', 'required', new EgyptPhoneNumber],
            'market_category_id' => ['required'],
            'city_id' => ['required'],
            'store_name' => ['string', 'required', Rule::unique('markets', 'store_name')->where('city_id', $this->city_id)],
        ];
    }
}
