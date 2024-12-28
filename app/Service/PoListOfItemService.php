<?php

namespace App\Service;

use App\Models\PoListOfItem;
use App\Models\TaxSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\formatDate;

class PoListOfItemService
{

    private static int $perPage = 10;


    public function query(): Builder
    {
        return PoListOfItem::with('supplier', 'branch');
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->query()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $listOfItem): LengthAwarePaginator
    {
        $data = $listOfItem->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'sn' => $item->sn,
                'date' => formatDate($item->date),
                'name' => $item->item->name,
                'qty' => $item->qty,
                'unit_price' => 'Rp ' . number_format($item->unit_price, 2),
                'shipping_cost' => $item->shipping_cost,
                'supplier' => $item->supplier?->name,
                'ppn' => $item->ppn,
                'status' => $item->status,
                'total_price' => $item->total_price,
                'travel_letter_receipt' => $item->travel_letter_receipt,
            ];
        });

        $listOfItem->setCollection($data);
        return $listOfItem;
    }

    public function search(Request $request)
    {

        $data = $this->query()->when('');


    }

    public function getPPN()
    {
        return TaxSetting::where('name', 'PPN')->first()?->rate;
    }
}
