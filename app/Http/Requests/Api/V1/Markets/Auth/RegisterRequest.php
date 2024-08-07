<?php

namespace App\Http\Requests\Api\V1\Markets\Auth;

use App\Rules\EgyptPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => ['required','string'],
            'middle_name' => ['required','string'],
            'last_name' => ['required','string'],
            'phone_number' => ['required','unique:markets,phone_number',new EgyptPhoneNumber],
            'password' => ['required','confirmed'],
            'city_id' => ['required','exists:cities,id'],
            'location_details' => ['string'],
            'market_category_id' => ['required','exists:market_categories,id'],
            'store_name' => ['required'],
            'deviceToken'=>'nullable',
        ];
    }
}
