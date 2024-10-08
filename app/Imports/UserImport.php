<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UserImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{

    use Importable;

    private Collection $branch;

    public function __construct()
    {
        $this->branch = Branch::all(['id', 'name'])->pluck('id');
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'exists:branches,id'],
            'absent_id' => ['required'],
            '*.absent_id' => function ($attribute, $value, $onFailure) {
                $user = User::where('absent_id', $value)->first();
                if ($user) {
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
            'nama' => ['required'],
            'penempatan' => ['required'],
            'tanggal_masuk' => ['required'],
        ];
    }

    public function model(array $row): User
    {
        return new User([
            'branch_id' => $this->branch->where('name', $row['cabang'])->first()->id ?? null,
            'absent_id' => (int)$row['absen_id'],
            'nip' => (int)$row['nik'],
            'name' => $row['nama'],
            'placement' => $row['penempatan'],
            'join_date' => Carbon::instance(Date::excelToDateTimeObject((int)$row['tanggal_masuk'])),
            'password' => Hash::make('password'),
        ]);
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
