<?php

namespace App\Support\Inventory\ReturnedItem\Service;

use AllowDynamicProperties;
use App\Models\StockWithdrawal;
use App\Support\Inventory\ReturnedItem\Repository\ReturnedItemRepository;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class ReturnedItemService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->returnedItemRepository = new ReturnedItemRepository();
    }

    public function getReturnedItemByStockWithdrawalId(StockWithdrawal $stockWithdrawal): LengthAwarePaginator
    {
        $returnedItems = $this->returnedItemRepository->getReturnedItemByStockWithdrawalId($stockWithdrawal)->paginate(self::$perPage);
        dd($returnedItems);
        return self::formattedData($returnedItems);
    }


    private static function formattedData(LengthAwarePaginator $returnedItems): LengthAwarePaginator
    {
        $data = $returnedItems->getCollection()->map(function ($returnedItem) {
            return [
                'id' => $returnedItem->id,
                'item_name' => $returnedItem->stockWithdrawalItem->stockWithdrawal->stock->item?->name,
            ];
        });

        $returnedItems->setCollection($data);
        return $returnedItems;
    }
}
