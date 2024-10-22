<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Pole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PoleImport implements ToModel, WithHeadingRow, WithValidation
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
    public function model(array $row): Pole|Model|null
    {
        return new Pole([
            'branch_id' => $this->branch->where('name', $row['cabang'])->first()->id,
            'diameter' => $row['diameter'],
            'length' => $row['panjang'],
            'region' => $row['wilayah'],
            'code' => $row['kode'],
            'lat' => $row['latitude'],
            'long' => $row['longitude'],
            'cut_off_date' => Carbon::instance(Date::excelToDateTimeObject((int)$row['cut_off_data'])),
        ]);
    }


    public function customValidationMessages(): array
    {
        return [
            'cabang.required' => 'Cabang tidak boleh kosong',
            'cabang.exists' => 'Cabang yang dipilih tidak ada',
            'diameter.required' => 'Diameter tidak boleh kosong',
            'panjang.required' => 'Panjang tidak boleh kosong',
            'wilayah.required' => 'Wilayah tidak boleh kosong',
            'kode.required' => 'Kode tidak boleh kosong',
            'latitude.required' => 'Latitude tidak boleh kosong',
            'longitude.required' => 'Longitude tidak boleh kosong',
            'cut_off_data.required' => 'Cut off data tidak boleh kosong',
        ];
    }

    public function rules(): array
    {
        return [
            'cabang' => ['required', Rule::exists('branches', 'name')],
            'diameter' => ['required'],
            'panjang' => ['required'],
            'wilayah' => ['required'],
            'kode' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'cut_off_data' => ['required'],
        ];
    }
}
