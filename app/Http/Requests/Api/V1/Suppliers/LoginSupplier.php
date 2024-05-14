<?php

namespace App\Http\Requests\Api\V1\Suppliers;

use App\Rules\EgyptPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class LoginSupplier extends FormRequest
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
            'phone_number' => ['required','string','exists:suppliers,phone_number',new EgyptPhoneNumber],
            'password' => 'required|string|min:6',
        ];
    }
}
