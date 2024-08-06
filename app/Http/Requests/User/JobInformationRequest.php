<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobInformationRequest extends FormRequest
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
            'department_id' => ['required', 'exists:departments,id'],
            'join_date' => ['required', 'date'],
            'fixed_salary' => ['required'],
            'contract_status' => [
                'required',
                Rule::in('Tetap', 'Kontrak', 'Vendor', 'Training', 'Magang', 'Freelance', 'Non Karyawan'),
            ],
            'bank_account_number' => ['required'],
            'bpjs_kes' => [
                'required',
                Rule::in('ya', 'tidak'),
            ],
            'no_kis' => [
                Rule::requiredIf(static function () {
                    return request()->bpjs_kes === 'ya';
                }),
            ],
            'bpjs_ket' => [
                'required',
                Rule::in('ya', 'tidak'),
            ],
            'no_kpj' => [
                Rule::requiredIf(static function () {
                    return request()->bpjs_ket === 'ya';
                }),
            ],
            'placement_id' => [
                'required',
                Rule::exists('user_placements', 'id'),
            ],
            'sk_file' => ['required', 'mimes:pdf', 'max:2048'],
            'contract_file' => ['required', 'mimes:pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.required' => 'Department tidak boleh kosong.',
            'department_id.exists' => 'Department tidak ditemukan.',
            'join_date.required' => 'tanggal mulai bekerja tidak boleh kosong.',
            'fixed_salary.required' => 'Gaji pokok tidak boleh kosong.',
            'contract_status.required' => 'Status kontrak tidak boleh kosong.',
            'bank_account_number.required' => 'No. rekening tidak boleh kosong',
            'bpjs_kes.required' => 'BPJS Kesehatan tidak boleh kosong.',
            'bpjs_kes.in' => 'BPJS Ketenagakerjaan tidak valid.',
            'no_kis.required_if' => 'Nomor KIS tidak boleh kosong.',
            'bpjs_ket.required' => 'BPJS ket is required.',
            'bpjs_ket.in' => 'BPJS Kesehatan tidak valid.',
            'no_kpj.required_if' => 'Nomor KPJ tidak boleh kosong.',
            'placement_id.required' => 'Placement is required.',
            'sk_file.required' => 'SK file is required.',
            'contract_file.required' => 'Contract file is required.',
            'sk_file.mimes' => 'SK file tidak valid.',
            'contract_file.mimes' => 'File kontrak kerja  tidak valid.',
            'sk_file.max' => 'File SK tidak boleh lebih dari 2MB.',
            'contract_file.max' => 'File kontrak kerja tidak boleh lebih dari 2MB.',
        ];
    }
}
