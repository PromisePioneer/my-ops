<?php

namespace App\Http\Requests\Utilities\CompanyProfile;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileRequest extends FormRequest
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
            'name' => ['required'],
            'address' => ['required'],
            'npwp' => ['required'],
            'bank' => ['required'],
            'bank_account_number' => ['required'],
            'bank_account_name' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Perusahaan tidak boleh kosong',
            'address.required' => 'Alamat tidak boleh kosong',
            'npwp.required' => 'NPWP tidak boleh kosong',
            'bank.required' => 'Bank tidak boleh kosong',
            'bank_account_number.required' => 'No. Rekening tidak boleh kosong',
            'bank_account_name.required' => 'Nama Bank tidak boleh kosong',
        ];
    }
}
