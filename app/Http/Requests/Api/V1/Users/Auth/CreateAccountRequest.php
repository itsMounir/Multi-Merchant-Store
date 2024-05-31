<?php

namespace App\Http\Requests\Api\V1\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EgyptPhoneNumber;

class CreateAccountRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone_number' => ['required',  new EgyptPhoneNumber],
            'password' => ['required','min:6'],
            'email' => ['required', 'email'],
            'role' => ['required', 'not_in:super_admin']
        ];
    }
}
