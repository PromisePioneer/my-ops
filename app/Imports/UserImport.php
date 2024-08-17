<?php

namespace App\Imports;

use App\Models\User;
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
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'placement' => "Pusat",
        ]);
    }
}
