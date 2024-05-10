<?php

namespace App\Http\Requests\Api\V1\users;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'supplier_id'=> ['required', 'exists:suppliers,id'],
            'image'=> ['required', 'image','mimes:jpeg,png,jpg,gif,svg'],
        ];
    }
}
