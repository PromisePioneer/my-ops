<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GoodsRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'unit_type_id' => ['required', 'string'],
            'category_id' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Nama barang tidak boleh kosong',
            'unit_type_id.required' => 'Tipe satuan tidak boleh kosong',
            'category_id.required' => 'Kategori tidak boleh kosong',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'unit_type_id.exists' => 'Tipe satuan tidak ditemukan',
        ];
    }
}
