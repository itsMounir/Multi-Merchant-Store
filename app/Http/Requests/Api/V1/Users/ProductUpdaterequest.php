<?php

namespace App\Http\Requests\Api\V1\Users;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdaterequest extends FormRequest
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
        $product = $this->route('id');
        return [
            'name' => ['required', 'string', 'unique:products,name,' . $product . ',id', 'max:255'],
            'discription' => ['required', 'string', 'max:300'],
            'size' => ['required', 'numeric'],
            'size_of' => ['required', 'string'],
            'product_category_id' => ['required', 'numeric'],
        ];
    }
}
