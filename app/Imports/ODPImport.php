<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\ODP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ODPImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     *
     * @return Model|null
     */
    public function model(array $row): Model|ODP
    {
        return new ODP([
            'branch_id' => Branch::where('name', $row['cabang'])->first()->id,
            'name' => $row['nama_odp'],
            'classification' => $row['klasifikasi'],
            'passive_splitter' => $row['jenis_passive_splitter'],
            'lat' => $row['latitude'],
            'long' => $row['longitude'],
            'max_capacity' => $row['kapasitas_maksimal'],
            'used_capacity' => $row['kapasitas_terpakai'],
            'cut_off_date' => Carbon::instance(Date::excelToDateTimeObject((int)$row['cutoff_data'])),
        ]);
    }
}
