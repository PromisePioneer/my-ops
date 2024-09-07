<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BPJSKetRequest extends FormRequest
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
            'name' => ['required'],
            'company_rate' => ['nullable', 'numeric'],
            'employee_rate' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'company_rate.numeric' => 'Bagian Perusahaan harus berupa angka',
            'employee_rate.numeric' => 'Bagian Pegawai harus berupa angka',
        ];
    }
}
