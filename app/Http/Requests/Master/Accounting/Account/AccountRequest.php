<?php

namespace App\Http\Requests\Master\Accounting\Account;

use App\Models\Account;
use App\Models\Company;
use Closure;
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

        $ifAccountExists = $this->isAccountExists($request);

        return [
            'name' => [
                'required',
            ],
            'code' => [
                'required',
                $ifAccountExists

            ],
            'parent_id' => [
                'nullable',
            ],
            'category_id' => [
                'nullable',
                Rule::exists('account_categories', 'id')
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
            'category_id.exists' => 'Kategori tidak valid!',
            'company_id.exists' => 'Perusahaan tidak valid!',
            'company_id.required' => 'Perusahaan tidak boleh kosong!',
        ];
    }


    public function isAccountExists(Request $request): Closure
    {
        return function ($attribute, $value, $fail) use ($request) {
            $account = Account::where('code', $value)
                ->where('company_id', $request->company_id)
                ->first();

            $company = Company::where('id', $request->company_id)->first()?->name;

            if (!empty($account) && empty($request->parent_id)) {
                $fail("Akun Dengan {$account->code} sudah terdaftar di perusahaan {$company}");
            }

        };
    }
}
