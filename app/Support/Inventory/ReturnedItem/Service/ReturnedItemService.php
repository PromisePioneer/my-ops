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
        return self::formattedData($returnedItems);
    }


    private static function formattedData(LengthAwarePaginator $returnedItems): LengthAwarePaginator
    {
        $data = $returnedItems->getCollection()->map(function ($returnedItem) {
            $consumedQty = 0;

            if ($returnedItem->stockWithdrawalItem->qty) {
                $consumedQty += $returnedItem->stockWithdrawalItem?->qty - $returnedItem->remaining_qty - $returnedItem->broken_qty;
            }


            if ($consumedQty < 0) {
                $consumedQty = 0;
            }


            $item = $returnedItem->stockWithdrawalItem->stock->transaction?->item ?? $returnedItem->stockWithdrawalItem->stock->initialInventoryBalance->item;

            return [
                'id' => $returnedItem->id,
                'code' => $returnedItem->stockWithdrawalItem->code,
                'item_name' => $item->name,
                'qty' => $returnedItem->stockWithdrawalItem->qty . $item->unitType->name,
                'consumed_qty' => $consumedQty,
                'returned_qty' => $returnedItem->remaining_qty ?? 0,
                'broken_qty' => $returnedItem->broken_qty,
                'status' => $returnedItem->status,
            ];
        });

        $returnedItems->setCollection($data);
        return $returnedItems;
    }
}
