<?php

namespace App\Http\Requests\Api\V1\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EgyptPhoneNumber;

class LoginRequest extends FormRequest
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
            'phone_number' => ['required', new EgyptPhoneNumber],
            'password' => ['required', 'string']
        ];
    }
}
