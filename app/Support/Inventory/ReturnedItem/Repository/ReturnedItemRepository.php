<?php

namespace App\Support\Inventory\ReturnedItem\Repository;

use App\Models\ReturnedItem;
use App\Models\StockWithdrawal;
use Illuminate\Database\Eloquent\Builder;

class ReturnedItemRepository
{
    public function getReturnedItemByStockWithdrawalId(StockWithdrawal $stockWithdrawal): Builder
    {
        return ReturnedItem::with('stockWithdrawalItem.stock.transaction.item.unitType')
            ->whereHas('stockWithdrawalItem', function ($query) use ($stockWithdrawal) {
                $query->where('stock_withdrawal_id', $stockWithdrawal->id);
            });
    }
}
