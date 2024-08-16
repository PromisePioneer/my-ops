<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     * @return User
     */
    public function model(array $row): User
    {
        return new User([
            'absent_id' => $row['absen'],
            'nip' => $row['nik'],
            'name' => $row['nama'],
            'join_date' => Carbon::createFromFormat('d/m/Y', $row['join_date']),
            'email' => fake()->unique(true)->safeEmail(),
            'password' => Hash::make('password'),
            'placement' => "Pusat",
        ]);
    }
}
