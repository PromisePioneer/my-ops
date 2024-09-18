<?php

namespace App\Imports;

use App\Models\BroadbandPacket;
use App\Models\SaleBonus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SalesBonusImport implements ToModel, WithHeadingRow
{

    private Collection $user;
    private Collection $packet;

    public function __construct()
    {
        $this->user = User::all();
        $this->packet = BroadbandPacket::all();
    }


    public function model(array $row)
    {
        return SaleBonus::create([
            'date_active' => Carbon::instance(Date::excelToDateTimeObject((int)$row['tanggal_aktif'])),
            'customer_name' => $row['nama_pelanggan'],
            'packet_id' => $this->packet->where('name', $row['paket'])->first()->id,
            'user_id' => $this->user->where('name', $row['sales'])->first()->id,
            'amount' => $row['bonus'],
        ]);
    }
}
