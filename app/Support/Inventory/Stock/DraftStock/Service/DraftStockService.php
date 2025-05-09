<?php

namespace App\Support\Inventory\Stock\DraftStock\Service;

use AllowDynamicProperties;
use App\Models\DraftStock;
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
        $itemData = DraftStock::with('transaction')
            ->sum('qty');

        return $itemData;
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
            $unitType = $query->transaction->item->unitType->name ?? $query->initialInventoryBalance->item->unitType->name;


            return [
                'id' => $query->id,
                'transaction_number' => $query->transaction?->transaction_number ?? '-',
                'name' => $query->transaction?->item?->name ?? $query->initialInventoryBalance->item->name,
                'qty' => $query->qty . ' ' . $unitType,
            ];
        });

        $itemData->setCollection($data);
        return $itemData;
    }
}
