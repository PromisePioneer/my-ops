<?php

namespace App\Imports;

use App\Models\Master\Common\Branch;
use App\Models\ODP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ODPImport implements ToModel, WithHeadingRow, WithValidation
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
    public function model(array $row): Model|ODP
    {
        return new ODP([
            'branch_id' => $this->branch->where('name', $row['cabang'])->first()->id,
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


    public function rules(): array
    {
        return [
            'cabang' => ['required', Rule::exists('branches', 'name')],
            'nama_odp' => ['required'],
            'klasifikasi' => ['required', Rule::in('AS', 'Turunan')],
            'jenis_passive_splitter' => ['required', Rule::in('ODP', 'FAT', 'ODU')],
            'kapasitas_maksimal' => ['required'],
            'used_capacity' => ['required'],
            'cutoff_data' => ['required'],
        ];
    }
}
