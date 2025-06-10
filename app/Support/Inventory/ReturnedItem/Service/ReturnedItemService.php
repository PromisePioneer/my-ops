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

            if ($returnedItem->stockWithdrawalItem->qty_in_meter) {
                $consumedQty += $returnedItem->stockWithdrawalItem->qty_in_meter - $returnedItem->remaining_qty - $returnedItem->broken_qty;
            }

            return [
                'id' => $returnedItem->id,
                'code' => $returnedItem->stockWithdrawalItem->code,
                'item_name' => $returnedItem->stockWithdrawalItem->stock->item?->name,
                'qty' => $returnedItem->stockWithdrawalItem->qty . $returnedItem->stockWithdrawalItem->stock->item->unitType->name,
                'consumed_qty' => $consumedQty,
                'returned_qty' => $returnedItem->remaining_qty,
                'broken_qty' => $returnedItem->broken_qty,
                'status' => $returnedItem->status,
            ];
        });

        $returnedItems->setCollection($data);
        return $returnedItems;
    }
}
