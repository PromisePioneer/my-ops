<?php

namespace App\Http\Requests\Master\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'full_name' => ['required'],
            'company_name' => ['required'],
            'email' => [
                'required',
                'email',
                Rule::unique('contacts', 'email')
                    ->ignore($request->route('contact')),
            ],
            'phone_number' => ['required'],
            'identity_type' => [
                'required',
                Rule::in('ktp', 'sim', 'passport'),
            ],
            'identity_number' => [
                'required',
                Rule::unique('contacts', 'identity_number')
                    ->ignore($request->route('contact')),
            ],
            'fax' => ['required'],
            'npwp' => ['required'],
            'complete_address' => ['required'],
            'other_info' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Kolom nama lengkap wajib diisi.',
            'company_name.required' => 'Kolom nama perusahaan wajib diisi.',
            'email.required' => 'Kolom alamat email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.unique' => 'Email sudah terdaftar',
            'phone_number.required' => 'Kolom nomor telepon wajib diisi.',
            'identity_type.required' => 'Silakan pilih jenis identitas (KTP, SIM, Paspor).',
            'identity_type.in' => 'Jenis identitas yang dipilih tidak valid.',
            'identity_number.required' => 'Kolom nomor identitas wajib diisi.',
            'fax.required' => 'Kolom fax wajib diisi.',
            'npwp.required' => 'Kolom NPWP wajib diisi.',
            'complete_address.required' => 'Kolom alamat lengkap wajib diisi.',
            'other_info.required' => 'Kolom informasi lainnya wajib diisi.',
        ];
    }
}
