<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitTypeRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:10',
                Rule::unique('unit_types', 'name')->ignore(request()->route('unitType')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'nama tidak boleh kosong.',
            'name.max' => 'nama tidak boleh lebih dari 10 karakter.',
            'name.unique' => 'nama sudah terdaftar.',
        ];
    }
}
