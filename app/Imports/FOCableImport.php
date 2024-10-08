<?php

namespace App\Imports;

use App\Models\FOCable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class FOCableImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     *
     * @return Model|null
     */
    public function model(array $row): Model|FOCable|null
    {
//        dd($row);

        return new FOCable([
            'segment_id' => $row['segmen'],
            'classification' => $row['klasifikasi'],
            'cable_placement' => $row['letak_kabel'],
            'total_core' => $row['jumlah_core'],
            'cable_address' => $row['jalur_kabel'],
            'starting_point_lat' => $row['titik_awal_latitude'],
            'starting_point_long' => $row['titik_awal_longitude'],
            'ending_point_lat' => $row['titik_akhir_latitude'],
            'ending_point_long' => $row['titik_akhir_longitude'],
            'length' => $row['panjang_kabel'],
            'cut_off_date' => Carbon::instance(Date::excelToDateTimeObject((int)$row['tanggal_cut_off'])),
        ]);
    }
}
