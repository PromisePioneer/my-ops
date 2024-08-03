<?php

namespace App\Http\Requests\Master\AccountCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                Rule::unique('sub_accounts', 'code')->ignore(request()->route('subAccount'))
            ],
            'account_id' => [
                'required',
                Rule::exists('accounts', 'id')],
            'name' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode kategori tidak boleh kosong',
            'code.unique' => 'Kode kategori sudah terdaftar',
            'name.required' => 'Nama kategori tidak boleh kosong',
            'account_id.required' => 'Akun tidak boleh kosong',
            'account_id.exists' => 'Akun tidak terdaftar',
        ];
    }
}
