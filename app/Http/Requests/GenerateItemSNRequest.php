<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateItemSNRequest extends FormRequest
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
            'sn' => [
                'required',
                Rule::unique('goods_stock', 'sn')
                    ->ignore($this->route('goodsStock'))
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'sn.required' => 'Serial Number / Kode tidak boleh kosong',
            'sn.unique' => 'Serial Number / Kode sudah terdaftar'
        ];
    }
}
