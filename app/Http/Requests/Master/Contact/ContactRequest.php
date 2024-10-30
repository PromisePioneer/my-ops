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
            'company_name' => ['required', Rule::unique('contacts', 'company_name')],
            'email' => [
                'required',
                'email',
                Rule::unique('contacts', 'email')
                    ->ignore($request->route('contact') === null),
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
            'company_name.required' => 'Kolom nama perusahaan wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.unique' => 'Email sudah terdaftar',
            'phone_number.required' => 'Kolom nomor telepon wajib diisi.',
            'identity_type.in' => 'Jenis identitas yang dipilih tidak valid.',
        ];
    }
}
