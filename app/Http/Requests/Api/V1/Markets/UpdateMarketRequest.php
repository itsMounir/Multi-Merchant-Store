<?php

namespace App\Http\Requests\Api\V1\Markets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMarketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ($this->market->id == Auth::id());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string'],
            'middle_name'=> ['string'],
            'last_name'=> ['string'],
            'phone_number' => ['unique:markets,phone_number','digits:11'],
            'city_id' => ['exists:cities,id'],
            'market_category_id' => ['exists:market_categories,id'],
            'store_name' => [],
            'representator_code'=> [],
        ];
    }
}
