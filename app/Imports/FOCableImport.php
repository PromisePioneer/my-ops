<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\FOCable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class FOCableImport implements ToModel, WithHeadingRow, WithValidation
{

    private Collection $branch;

    public function __construct()
    {
        $this->branch = Branch::all(['id', 'name']);
    }


    /**
     * @param  array  $row
     *
     * @return Model|null
     */
    public function model(array $row): Model|FOCable|null
    {
        return new FOCable([
            'branch_id' => $this->branch->where('name', $row['cabang'])->first()->id,
            'segment_id' => $row['segmen'],
            'classification' => $row['klasifikasi'],
            'cable_placement' => $row['letak_kabel'],
            'total_core' => $row['jumlah_core'],
            'used_core' => $row['jumlah_core_terpakai'],
            'cable_address' => $row['jalur_kabel'],
            'starting_point_lat' => $row['titik_awal_latitude'],
            'starting_point_long' => $row['titik_awal_longitude'],
            'ending_point_lat' => $row['titik_akhir_latitude'],
            'ending_point_long' => $row['titik_akhir_longitude'],
            'length' => $row['panjang_kabel'],
            'cut_off_date' => Carbon::instance(Date::excelToDateTimeObject((int)$row['tanggal_cut_off'])),
        ]);
    }

    public function rules(): array
    {
        return [
            'cabang' => ['required', Rule::exists('branches', 'id')],
            'segmen' => ['required'],
            'klasifikasi' => ['required', Rule::in('Backbone', 'Backhaul', 'Fronthaul', 'Akses')],
            'letak_kabel' => ['required', Rule::in('Udara', 'Underground')],
            'jumlah_core' => ['required'],
            'jumlah_core_terpakai' => ['required'],
            'jalur_kabel' => ['required'],
            'titik_awal_latitude' => ['required'],
            'titik_awal_longitude' => ['required'],
            'titik_akhir_latitude' => ['required'],
            'titik_akhir_longitude' => ['required'],
            'panjang_kabel' => ['required'],
        ];
    }
}
