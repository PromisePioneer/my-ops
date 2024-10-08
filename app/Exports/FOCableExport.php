<?php

namespace App\Exports;

use AllowDynamicProperties;
use App\Models\FOCable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

#[AllowDynamicProperties] class FOCableExport implements FromCollection, WithHeadings
{

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $odp = FOCable::whereBetween('cut_off_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                return [
                    'segment_id' => $item->segment_id,
                    'classification' => $item->classification,
                    'cable_placement' => $item->cable_placement,
                    'cable_address' => $item->cable_address,
                    'total_core' => $item->total_core,
                    'starting_point_lat' => $item->starting_point_lat,
                    'starting_point_long' => $item->starting_point_long,
                    'ending_point_lat' => $item->ending_point_lat,
                    'ending_point_long' => $item->ending_point_long,
                    'length' => $item->length,
                    'cut_off_date' => $item->cut_off_date,
                ];
            });

        return collect($odp);
    }

    public function headings(): array
    {
        return [
            'SEGMEN',
            'KLASIFIKASI',
            'LETAK KABEL',
            'JUMLAH CORE',
            'JALUR KABEL',
            'TITIK AWAL (LATITUDE)',
            'TITIK AWAL (LONGITUDE)',
            'TITIK AKHIR (LATITUDE)',
            'TITIK AKHIR (LONGITUDE)',
            'PANJANG KABEL',
            'TANGGAL CUT OFF',
        ];
    }
}
