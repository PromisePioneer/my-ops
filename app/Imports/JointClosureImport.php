<?php

namespace App\Imports;

use App\Models\JointClosure;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JointClosureImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     *
     * @return Model|JointClosure|null
     */
    public function model(array $row): Model|JointClosure|null
    {
        return new JointClosure([
            'code_id' => $row['code_id'],
            'region' => $row['region'],
            'fo_cable_id',
            'lat',
            'long',
            'cut_off_date',
        ]);
    }
}
