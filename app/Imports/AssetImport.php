<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Master\Common\Branch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AssetImport implements ToModel, WithHeadingRow
{
    private Branch $branch;

    public function __construct()
    {
        $this->branch = new Branch();
    }


    /**
     * @param  array  $row
     *
     * @return Model|Asset|null
     */
    public function model(array $row): Model|Asset|null
    {
        return new Asset([
            'branch_id' => Branch::where('name', $row['cabang'])->first()->id,
            'debit_account_id' => Account::where('name', $row['akun_debit'])->first()->id,
            'credit_account_id' => Account::where('name', $row['akun_debit'])->first()->id,
            'date_received' => Carbon::instance(Date::excelToDateTimeObject((int)$row['tanggal_perolehan'])),
            'name' => $row['nama'],
            'unit' => $row['unit'],
            'useful_life' => $row['masa_manfaat'],
            'price_per_unit' => $row['harga_per_unit'],
            'total_price' => $row['harga_per_unit'] * $row['unit'],
        ]);
    }
}
