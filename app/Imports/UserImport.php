<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UserImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithCalculatedFormulas
{
    use Importable;

    private Collection $branch;

    public function __construct()
    {
        $this->branch = Branch::all(['id', 'name', 'code'])->pluck('id');
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', Rule::exists('branches', 'id')],
            'absent_id' => ['required', Rule::unique('users', 'absent_id')],
            'company_id' => ['required'],
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
            'branch_id' => Branch::where('code', $row['cabang'])->first()?->id,
            'absent_id' => (int)$row['absen_id'],
            'company_id' => Company::where('code', $row['perusahaan'])->first()?->id,
            'nip' => (int)$row['nik'],
            'name' => $row['nama'],
            'placement' => $row['penempatan'],
            'join_date' => Carbon::parse($row['tanggal_masuk']),
            'password' => Hash::make('password'),
        ]);
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
