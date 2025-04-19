<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SubAccountImport implements ToModel, WithHeadingRow, WithValidation
{
    private $subAccount;

    private $account;

    public function __construct()
    {
        $this->subAccount = SubAccount::join('accounts', 'accounts.id', 'sub_accounts.account_id')->where('branch_id', Auth::user()->branch_id)->get();
    }

    public function rules(): array
    {
        return [
            'kode' => 'required',
            'nama' => 'required',
            '*.kode' => function ($attribute, $value, $onFailure) {
                $subAccount = $this->subAccount->where('sub_accounts.code', $value)->first();
                if ($subAccount) {
                    $onFailure('Data kategori akun sudah ada.');
                }
            },
            'akun' => 'required',
            '*.nama' => function ($attribute, $value, $onFailure) {
                $subAccount = $this->subAccount->where('name', $value)->first();
                if ($subAccount) {
                    $onFailure('Data kategori akun sudah ada.');
                }
            },
        ];
    }

    public function model(array $row)
    {

        return SubAccount::create([
            'account_id' => Account::where('branch_id', Auth::user()->branch_id)->where('name', $row['akun'])->pluck('id')->first(),
            'code' => $row['kode'],
            'name' => $row['nama'],
        ]);
    }
}
