<?php

namespace App\Exports;

use AllowDynamicProperties;
use App\Models\Pole;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

#[AllowDynamicProperties] class PoleExport implements FromCollection, WithHeadings
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
        return Pole::whereBetween('cut_off_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                return [
                    'diameter' => $item->diameter,
                    'panjang' => $item->length,
                    'wilayah' => $item->region,
                    'code' => $item->code,
                    'coordinates' => $item->lat.','.$item->long,
                    'cut_off_date' => $item->cut_off_date,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Diameter',
            'Panjang',
            'Wilayah',
            'Kode Tiang',
            'Koordinat',
            'Tanggal Cut Off',
        ];
    }


}
