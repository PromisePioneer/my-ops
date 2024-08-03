<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('permissions', 'name')
                    ->ignore(request()->route('permission'))
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Nama permission tidak boleh kosong',
            'name.unique' => 'Nama permission sudah terdaftar',
        ];
    }
}
