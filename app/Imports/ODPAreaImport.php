<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\ODPArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ODPAreaImport implements ToModel, WithHeadingRow, WithValidation
{

    use Importable;

    private Collection $branch;

    public function __construct()
    {
        $this->branch = Branch::all(['id', 'name'])->pluck('id');
    }

    public function rules(): array
    {
        return [
            'cabang' => ['required'],
            'kode' => ['required'],
        ];
    }


    public function customValidationMessages(): array
    {
        return [
            'cabang.required' => 'Cabang tidak boleh kosong.',
            'cabang.exists' => 'Cabang yang di input tidak ada.',
            'kode.required' => 'Kode tidak boleh kosong.',
            'kode.unique' => 'Kode sudah terdaftar.',
        ];
    }

    public function model(array $row): Model|ODPArea|null
    {
        return new ODPArea([
            'branch_id' => $this->branch->where('name', $row['cabang'])->first(),
            'code' => $row['kode'],
        ]);
    }


}
