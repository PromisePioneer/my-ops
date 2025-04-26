<?php

namespace App\Support\Inventory\Stock\DraftStock\Service;

use AllowDynamicProperties;
use App\Models\ItemCollection;
use App\Support\Inventory\Stock\DraftStock\Repository\DraftStockServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class DraftStockService
{


    private static int $perPage = 10;


    public function __construct()
    {
        $this->draftStockRepostitory = new DraftStockServiceRepository();
    }


    public function getDraftStockQty()
    {
        $itemData = ItemCollection::whereHas('draftStock')->limit(10)->get();
        return $itemData->map(function ($itemData) {
            return [
                'id' => $itemData->id,
                'name' => $itemData->name,
                'total' => number_format($itemData->draftStock->sum('qty'), 2, '.', '.'),
            ];
        });
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $items = $this->draftStockRepostitory->getDraftStockQuery()->paginate(self::$perPage);
        return self::formattedData($request, $items);
    }


    public function filter(Request $request)
    {
        $query = $this->draftStockRepostitory->getDraftStockQuery();
        $filter = DraftStockQueryFilter::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($request, $filter);
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
