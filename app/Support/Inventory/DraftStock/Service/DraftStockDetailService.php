<?php

namespace App\Support\Inventory\DraftStock\Service;

use AllowDynamicProperties;
use App\Models\ItemCollection;
use App\Support\Inventory\StockManagement\DraftStock\Repository\DraftStockRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class DraftStockDetailService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->draftStockRepository = new DraftStockRepository();
    }

    public function draftStockByItemId(ItemCollection $itemCollection, Request $request): LengthAwarePaginator
    {
        $query = $this->draftStockRepository
            ->getDraftStockByItemId($itemCollection, $request)
            ->paginate(self::$perPage);
        $data = $query->getCollection()->map(function ($draftStock) {
            return [
                'id' => $draftStock->id,
                'transaction_number' => $draftStock->transaction?->transaction_number ?? null,
                'qty' => $draftStock->qty,
            ];
        });


        $query->setCollection($data);
        return $query;
    }
}
