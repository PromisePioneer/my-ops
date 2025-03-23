<?php

namespace App\Http\Requests\Master\Common\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
                'string',
                Rule::unique('roles', 'name')
                    ->ignore(request()->route('role')),
            ],
            'department_id' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Role tidak boleh kosong.',
            'name.unique' => 'Nama Role sudah terdaftar.',
            'department_id.required' => 'Department tidak boleh kosong.',
        ];
    }
}
