<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UserImport implements ToModel, WithHeadingRow
{


    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'exists:branches,id'],
            'absent_id' => ['required'],
            '*.absent_id' => function ($attribute, $value, $onFailure) {
                $user = User::where('absent_id', $value)->first();
                if (!$user) {
                    $onFailure('Absen ID sudah terdaftar');
                }
            },
            'nip' => ['required'],
            '*.nip' => function ($attribute, $value, $onFailure) {
                $user = User::where('nik', $value)->first();
                if ($user) {
                    $onFailure('nik sudah terdaftar');
                }
            },
            'Nama' => ['required'],
            'Penempatan' => ['required'],
            'Tanggal Masuk' => ['required', 'date'],
        ];
    }

    /**
     * @param  array  $row
     * @return User
     */
    public function model(array $row): User
    {
        return User::create([
            'branch_id' => Branch::where('name', $row['cabang'])->pluck('id')->first() ?? null,
            'absent_id' => (int) $row['absen_id'],
            'nip' => (int) $row['nik'],
            'name' => $row['nama'],
            'placement' => $row['penempatan'],
            'join_date' => Carbon::instance(Date::excelToDateTimeObject($row['tanggal_masuk'])),
            'password' => Hash::make('password'),
        ]);
    }
}
