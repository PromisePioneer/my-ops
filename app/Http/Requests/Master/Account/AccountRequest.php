<?php

namespace App\Http\Requests\Master\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => [
                'nullable',
                Rule::exists('branches', 'id'),
            ],
            'name' => [
                'required',
            ],
            'code' => [
                'required',
                Rule::unique('accounts', 'code')
                    ->where(function ($query) {
                        return $query->where('branch_id', $this->branch_id);
                    })->ignore($this->route('account')), // Ensure uniqueness for code during update
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.unique' => 'Nama sudah terdaftar',
            'code.required' => 'Kode tidak boleh kosong',
            'code.unique' => 'Kode sudah terdaftar',
            'branch_id.required' => 'Branch tidak boleh kosong',
            'branch_id.exists' => 'Branch tidak ditemukan',
        ];
    }
}
