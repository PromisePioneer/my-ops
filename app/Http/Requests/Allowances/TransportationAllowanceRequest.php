<?php

namespace App\Http\Requests\Allowances;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransportationAllowanceRequest extends FormRequest
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
            'user_id' => ['required', 'integer', ' exists:users,id'],
            'date' => ['required', 'date'],
            'amount' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'Karyawan tidak boleh kosong',
            'user_id.integer' => 'Karyawan tidak valid',
            'user_id.exists' => 'Karyawan tidak valid',
            'date.required' => 'Karyawan tidak boleh kosong',
            'date.date' => 'Karyawan tidak valid',
            'amount.required' => 'Karyawan tidak boleh kosong',
        ];
    }
}
