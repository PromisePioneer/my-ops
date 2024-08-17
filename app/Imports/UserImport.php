<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     * @return User
     * @throws Exception
     */
    public function model(array $row): User
    {
        $maxRetries = 3;
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                return DB::transaction(function () use ($row) {
                    return User::create([
                        'absent_id' => $row['absen'],
                        'nip' => $row['nik'],
                        'name' => $row['nama'],
                        'join_date' => Carbon::createFromFormat('d/m/Y', $row['join_date']),
                        'email' => fake()->unique(true)->safeEmail(),
                        'password' => Hash::make('password'),
                        'placement' => "Pusat",
                    ]);
                });
            } catch (QueryException $e) {
                if ($e->getCode() == '40001') { // Deadlock error code
                    $attempt++;
                    sleep(1); // Tunggu sebentar sebelum mencoba lagi
                } else {
                    throw $e; // Lempar kembali exception jika bukan deadlock
                }
            }
        }

        throw new Exception('Failed to insert after multiple retries.');
    }
}
