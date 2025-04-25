<?php

namespace App\Support\Inventory\Stock;

use App\Models\DraftStock;
use App\Models\ItemCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DraftStockService
{


    private static int $perPage = 10;
    public function getDraftStockQty()
    {
        $itemData = ItemCollection::whereHas('draftStock')->limit(10)->get();
        return $itemData->map(function ($itemData) {
            return [
                'id' => $itemData->id,
                'name' => $itemData->name,
                'total' => $itemData->draftStock->sum('qty'),
            ];
        });
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $items = DraftStock::with('item', 'item.category', 'transaction')
            ->wherehas('item.category', function ($query) {
                $query->where('name', '!=', 'Kategori 4');
            })->where('qty', '>', 0)
            ->orderBy('created_at')
            ->paginate(self::$perPage);
        return self::formattedData($request, $items);
    }


    private static function formattedData(Request $request, LengthAwarePaginator $itemData): LengthAwarePaginator
    {
        $data = $itemData->getCollection()->map(function ($query) use ($request) {
            return [
                'id' => $query->id,
                'transaction_number' => $query->transaction->transaction_number,
                'name' => $query->item->name,
                'qty' => $query->qty . ' ' . $query->item->unitType->name,
            ];
        });

        $itemData->setCollection($data);
        return $itemData;
    }
}
