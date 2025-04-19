<?php

namespace App\Exports;

use AllowDynamicProperties;
use App\Models\ODP;
use Carbon\Carbon;
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
            'Cabang',
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
        $odp = ODP::with('branch')->whereBetween('cut_off_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                return [
                    'branch' => $item->branch->name,
                    'name' => $item->name,
                    'classification' => $item->classification,
                    'passive_splitter' => $item->passive_splitter,
                    'lat' => $item->lat,
                    'long' => $item->long,
                    'max_capacity' => $item->max_capacity,
                    'used_capacity' => $item->used_capacity,
                    'capacities_left' => $item->max_capacity - $item->used_capacity,
                    'cut_off_date' => Carbon::parse($item->cut_off_date)->locale('id')
                        ->settings(['formatFunction' => 'translatedFormat'])
                        ->format('F Y'),
                ];
            });

        return collect($odp);
    }
}
