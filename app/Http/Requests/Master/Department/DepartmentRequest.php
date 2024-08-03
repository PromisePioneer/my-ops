<?php

namespace App\Http\Requests\Master\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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
                Rule::unique('departments', 'code')->ignore(request()->route('department')),
            ],
            'name' => [
                'required',
                Rule::unique('departments', 'name')->ignore(request()->route('department')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'kode tidak boleh kosong',
            'code.unique' => 'kode sudah terdaftar',
            'name.required' => 'nama tidak boleh kosong',
            'name.unique' => 'nama sudah terdaftar',
        ];
    }
}
