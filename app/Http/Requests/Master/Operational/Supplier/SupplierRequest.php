<?php

namespace App\Http\Requests\Master\Operational\Supplier;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
            'code' => ['required', 'min:3', 'max:3'],
            'name' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'province' => ['required'],
            'country' => ['required'],
            'postal_code' => ['required'],
            'fax' => ['nullable'],
            'email' => ['nullable', 'email:email'],
            'phone_number' => ['nullable'],
            'bank_account_number' => ['required'],
            'bank_account_name' => ['required'],
            'bank_name' => ['required'],
            'npwp' => ['nullable'],
            'description' => ['nullable'],
            'tax_type' => ['required', Rule::in('PKP', 'NON PKP')],
        ];
    }


    public function messages(): array
    {
        return [
            'code.required' => 'Kode tidak boleh kosong.',
            'code.min' => 'Kode minimal 3 karakter.',
            'code.max' => 'Kode maksimal 3 karakter.',
            'name.required' => 'Nama tidak boleh kosong.',
            'address.required' => 'Alamat tidak boleh kosong.',
            'city.required' => 'Kota tidak boleh kosong.',
            'province.required' => 'Provinsi tidak boleh kosong.',
            'country.required' => 'Negara tidak boleh kosong.',
            'postal_code.required' => 'Kode Pos tidak boleh kosong.',
            'bank_account_number.required' => 'Nomor Rekening Bank tidak boleh kosong.',
            'bank_account_name.required' => 'Nama Rekening Bank tidak boleh kosong.',
            'bank_name.required' => 'Nama Bank tidak boleh kosong.',
            'tax_type.required' => 'Tipe Pajak tidak boleh kosong.',
            'tax_type.in' => 'Tipe Pajak tidak valid.',
        ];
    }
}
