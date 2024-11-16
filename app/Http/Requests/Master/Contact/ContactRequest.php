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
            'pic_name' => ['required'],
            'pic_position' => ['required'],
            'company_name' => ['required', Rule::unique('contacts', 'company_name')->ignore($request->route('contact'))],
            'company_code' => [
                'required',
                Rule::unique('contacts', 'company_code')
                    ->ignore($request->route('contact')),
                'min:3',
                'max:3'
            ],
            'email' => [
                'nullable',
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
                'nullable',
                Rule::unique('contacts', 'identity_number')
                    ->ignore($request->route('contact') === null),
            ],
            'fax' => ['nullable', Rule::unique('contacts', 'fax')],
            'npwp' => ['nullable', Rule::unique('contacts', 'npwp')],
            'complete_address' => ['nullable'],
            'other_info' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'pic_name.required' => 'Kolom nama lengkap wajib diisi.',
            'pic_position.required' => 'Kolom jabatan wajib diisi.',
            'company_name.required' => 'Kolom nama perusahaan wajib diisi.',
            'company_code.required' => 'Kolom kode Perusahaan wajib diisi.',
            'company_code.min' => 'Kolom kode perusahaan minimal 3 karakter.',
            'company_code.max' => 'Kolom kode perusahaan maksimal 3 karakter.',
            'company_code.unique' => 'Kolom kode perusahaan sudah terdaftar.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.unique' => 'Email sudah terdaftar',
            'phone_number.required' => 'Kolom nomor telepon wajib diisi.',
            'identity_type.in' => 'Jenis identitas yang dipilih tidak valid.',
        ];
    }
}
