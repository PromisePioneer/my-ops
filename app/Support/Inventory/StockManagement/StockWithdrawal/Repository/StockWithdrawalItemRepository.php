<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Repository;

use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;

class StockWithdrawalItemRepository
{

    public function findByStockWithdrawal(StockWithdrawal $stockWithdrawal)
    {
        return StockWithdrawalItem::with('stock', 'stock.itemCatalog')
            ->where('stock_withdrawal_id', $stockWithdrawal->id);
    }


}
