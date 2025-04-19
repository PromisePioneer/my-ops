<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccountImport implements ToModel, WithHeadingRow, WithValidation
{
    private Collection $account;

    public function __construct()
    {
        $this->account = Account::join('branches', 'accounts.branch_id', 'branches.id')
            ->select('accounts.*', 'branches.name as branch_name')
            ->get();
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'kode' => 'required',
            '*.cabang' => function ($attribute, $value, $onFailure) {
                $account = $this->account->where('branch_name', $value)->first();
                if ($account) {
                    $onFailure('Data akun sudah ada di cabang '.$value);
                }
            },
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nama.required' => 'Nama harus diisi.',
            'kode.required' => 'Kode harus diisi.',
            '*.cabang' => 'akun sudah ada dengan cabang ini.',
        ];
    }

    public function model(array $row): Account
    {
        return Account::create([
            'branch_id' => Auth::user()->branch_id,
            'name' => $row['nama'],
            'code' => $row['kode'],
        ]);
    }
}
