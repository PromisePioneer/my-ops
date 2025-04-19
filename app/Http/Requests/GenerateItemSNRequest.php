<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
    public function rules(Request $request): array
    {
        return [
            'serial_number' => [
                Rule::requiredIf($request->sn === null),
                Rule::unique('stock_has_sn', 'serial_number')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'serial_number.required' => 'Serial Number / Kode tidak boleh kosong',
            'serial_number.unique' => 'Serial Number / Kode sudah terdaftar'
        ];
    }
}
