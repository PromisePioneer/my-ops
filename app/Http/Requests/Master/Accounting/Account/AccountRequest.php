<?php

namespace App\Http\Requests\Master\Accounting\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'name' => [
                'required',
            ],
            'code' => [
                'required',
                Rule::unique('accounts', 'code')->ignore($this->route('account')),
            ],
            'parent_id' => [
                'nullable',
            ],
            'trial_balance_type' => [
                'required',
                'in:debit,credit'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.unique' => 'Nama sudah terdaftar',
            'code.required' => 'Kode tidak boleh kosong',
            'code.unique' => 'Kode sudah terdaftar',
            'trial_balance_type.required' => 'Tipe Saldo Awal / Neraca tidak boleh kosong',
            'trial_balance_type.in' => 'Tipe Saldo Awal / Neraca tidak valid!',
        ];
    }
}
