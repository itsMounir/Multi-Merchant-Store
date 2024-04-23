<?php

namespace App\Http\Requests\Api\V1\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class DistributionLocationRequest extends FormRequest
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
            'Distribution.id' => 'required|integer|exists:cities,id',
            'Distribution.to_sites' => 'required|array',
            'Distribution.to_sites.*' => 'required|integer|distinct|exists:cities,id',
        ];
    }
}
