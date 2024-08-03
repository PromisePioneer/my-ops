<?php

namespace App\Http\Requests\Master\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                Rule::unique('products', 'name')->ignore(request()->route('product'))
                ],
            'name' => 'required',
            'category' => 'required',
            'unit_price' => 'required',
        ];
    }


    public function messages(): array
    {
        return [
            'code.required' => 'Kode produk tidak boleh kosong',
            'name.required' => 'Nama product tidak boleh kosong',
            'category.required' => 'kategori tidak boleh kosong',
            'unit_price.required' => 'Harga produk tidak boleh kosong',
            'unit_price.numeric' => 'Harga produk tidak valid',
            'unit_price.between' => 'Harga produk tidak valid',
        ];
    }
}
