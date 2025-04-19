<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JobExperienceRequest extends FormRequest
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
            'company_name' => ['required', 'string'],
            'position' => ['required', 'string'],
            'responsibilities' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama Perusahaan tidak boleh koson .',
            'position.required' => 'Jabatan tidak boleh kosong.',
            'responsibilities.required' => 'Tanggung jawab tidak boleh kosong.',
            'start_date.required' => 'Tanggal Mulai tidak boleh kosong.',
            'start_date.date' => 'Tanggal Mulai tidak valid.',
            'end_date.required' => 'Tanggal Akhir tidak boleh kosong.',
            'end_date.date' => 'Tanggal Akhir Tidak valid.',
        ];
    }
}
