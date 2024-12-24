<?php

namespace App\Service;

use App\Models\ListOfItem;
use Illuminate\Pagination\LengthAwarePaginator;

class ListOfItemService
{

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = ListOfItem::with('supplier')->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $listOfItem): LengthAwarePaginator
    {
        $data = $listOfItem->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'sn' => $item->sn,
                'date' => $item->date,
                'name' => $item->name,
                'unit_price' => $item->unit_price,
                'shipping_cost' => $item->shipping_cost,
                'supplier' => $item->supplier?->name,
                'ppn' => $item->ppn,
                'total_price' => $item->total_price,
                'travel_letter_receipt' => $item->travel_letter_receipt,
            ];
        });

        $listOfItem->setCollection($data);
        return $listOfItem;
    }

    public function search()
    {

    }
}
