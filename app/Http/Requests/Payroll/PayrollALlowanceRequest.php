<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PayrollALlowanceRequest extends FormRequest
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
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required'],
            'amount' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'role_id.required' => 'Jabatan tidak boleh kosong',
            'role_id.exists' => 'Jabatan tidak ditemukan',
            'name' => 'nama tunjangan tidak boleh kosong',
            'amount' => 'jumlah tidak boleh kosong',
        ];
    }
}
