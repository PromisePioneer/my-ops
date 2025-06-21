<?php

namespace App\Support\Inventory\StockMutationAndWithdrawalRecord;

use AllowDynamicProperties;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalItemRepository;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class StockMutationAndWithdrawalRecordService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->stockWithdrawalItemRepository = new StockWithdrawalItemRepository();
    }


    public function stockWithdrawalItemData(ItemCatalog $itemCatalog, Stock $stock): LengthAwarePaginator
    {
        $query = $this->stockWithdrawalItemRepository->findByStockId($stock->id);
        if (!empty($itemCatalog->code)) {
            $query = $this->stockWithdrawalItemRepository
                ->findByStockIdAndCode($itemCatalog->stock_id, $itemCatalog->code);
        }

        return self::formattedStockWithdrawalItemData($query->paginate(self::$perPage));
    }


    public function formattedStockWithdrawalItemData(LengthAwarePaginator $withdrawalItem): LengthAwarePaginator
    {
        $data = $withdrawalItem->getCollection()->map(function ($withdrawalItem) {
            return [
                'id' => $withdrawalItem->id,
                'code' => $withdrawalItem->code,
                'name' => $withdrawalItem->name,
            ];
        });

        $withdrawalItem->setCollection($data);
        return $withdrawalItem;
    }


    public function create()
    {

    }

}
