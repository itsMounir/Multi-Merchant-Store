<?php

namespace App\Http\Requests\Api\V1\Users\auth;

use App\Rules\EgyptPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone_number' => ['required', 'unique:users,phone_number,' . $user . ',id', new EgyptPhoneNumber],
            'email' => ['required', 'unique:users,email,' . $user . ',id', 'email'],
            'role' => ['required', 'not_in:super_admin']

        ];
    }
}
