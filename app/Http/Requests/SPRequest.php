<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SPRequest extends FormRequest
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
            'start_date' => ['required', 'date'],
            'sp_type' => [
                'required',
                Rule::in('SP-1', 'SP-2', 'SP-3'),
            ],
            'data.*.list_of_reason' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Karyawan tidak boleh kosong',
            'user_id.exists' => 'Karyawan tidak valid',
            'start_date.required' => 'Tanggal tidak boleh kosong',
            'start_date.date' => 'Tanggal tidak valid',
            'sp_type.required' => 'Karyawan tidak boleh kosong',
            'sp_type.in' => 'Tipe SP tidak valid',
            'list_of_reason.required' => 'Alasan tidak boleh kosong',
        ];
    }
}
