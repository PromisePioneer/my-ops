<?php

namespace App\Imports;

use App\Models\Pole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PoleImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     *
     * @return Model|null
     */
    public function model(array $row): Pole|Model|null
    {
        return new Pole([
            'diameter' => $row['diameter'],
            'length' => $row['panjang'],
            'region' => $row['wilayah'],
            'code' => $row['kode_tiang'],
            'lat' => $row['latitude'],
            'long' => $row['longitude'],
            'cut_off_date' => Carbon::instance(Date::excelToDateTimeObject((int)$row['cut_off_data'])),
        ]);
    }
}
