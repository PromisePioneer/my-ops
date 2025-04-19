<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SKRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'sk_type' => ['required', Rule::in('Promosi', 'Demosi', 'Mutasi')],
            'branch_id' => ['required', 'exists:branches,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'Karyawan tidak boleh kosong',
            'user_id.exists' => 'Karyawan tidak ditemukan',
            'sk_type.required' => 'Jenis SK tidak boleh kosong',
            'sk_type.in' => 'Jenis SK tidak valid',
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang tidak ditemukan',
            'role_id.required' => 'Role tidak boleh kosong',
            'role_id.exists' => 'Role tidak ditemukan',
        ];
    }
}
