<?php

namespace App\Exports;

use AllowDynamicProperties;
use App\Models\ODP;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

#[AllowDynamicProperties] class ODPExport implements FromCollection, WithHeadings
{

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function headings(): array
    {
        return [
            'Nama Area',
            'Nama ODP',
            'Klasifikasi',
            'Jenis Passive Splitter',
            'Latitude',
            'Longitude',
            'Kapasitas Maksimal',
            'Kapasitas Terpakai',
            'Kapasitas Tersisa',
            'Cut Off',
        ];
    }

    public function collection(): Collection
    {
        $odp = ODP::with('area')->whereBetween('cut_off_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                return [
                    'nama_area' => $item->area->code,
                    'name' => $item->name,
                    'classification' => $item->classification,
                    'passive_splitter' => $item->passive_splitter,
                    'lat' => $item->lat,
                    'long' => $item->long,
                    'max_capacity' => $item->max_capacity,
                    'used_capacity' => $item->used_capacity,
                    'capacities_left' => $item->max_capacity - $item->used_capacity,
                    'cut_off_date' => $item->cut_off_date,
                ];
            });

        return collect($odp);
    }
}
