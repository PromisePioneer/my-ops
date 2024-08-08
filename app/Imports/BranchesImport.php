<?php

namespace App\Imports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BranchesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            'kode' => ['required', 'unique:branches,code'],
            'nama' => ['required', 'unique:branches,name'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'kode.required' => 'kode tidak boleh kosong',
            'kode.unique' => 'kode sudah terdaftar',
            'nama.required' => 'nama tidak boleh kosong',
            'nama.unique' => 'nama sudah terdaftar',
        ];
    }

    public function model(array $row): Branch
    {
        return new Branch([
            'code' => $row['kode'],
            'name' => $row['nama'],
        ]);
    }
}
