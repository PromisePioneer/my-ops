<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel
{
    /**
     * @param  array  $row
     * @return User
     */
    public function model(array $row): User
    {
        dd($row);
        return new User([
            'absent_id' => $row[0],
            'nip' => $row[1],
            'name' => $row[2],
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'placement' => "Pusat",
        ]);
    }
}
