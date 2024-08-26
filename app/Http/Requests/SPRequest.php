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
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'sp_type' => [
                'required',
                Rule::in('SP-1', 'SP-2', 'SP-3'),
            ],
            'reason' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'Karyawan tidak boleh kosong',
            'user_id.exists' => 'Karyawan tidak valid',
            'start_date.required' => 'Tanggal Awal tidak boleh kosong',
            'start_date.date' => 'Tanggal Awal tidak valid',
            'start_date.after_or_equal' => 'Tanggal Awal harus setelah hari ini atau hari ini',
            'end_date.required' => 'Tanggal Akhir tidak boleh kosong',
            'end_date.date' => 'Tanggal Akhir tidak valid',
            'end_date.after' => 'Tanggal Akhir tidak harus setelah tanggal awal.',
        ];
    }
}
