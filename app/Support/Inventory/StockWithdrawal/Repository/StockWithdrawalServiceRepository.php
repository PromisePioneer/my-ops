<?php

namespace App\Support\Inventory\StockWithdrawal\Repository;

use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use Illuminate\Database\Eloquent\Builder;

class StockWithdrawalServiceRepository
{
    public function getStockWithDrawalQuery(): Builder
    {
        return StockWithdrawal::with('branch', 'stocker', 'pic', 'stockWithdrawalByEmployee', 'stockWithdrawalItem');
    }


    public function getStockWithdrawalItems(StockWithdrawal $stockWithdrawal)
    {
        return StockWithdrawalItem::with('stock', 'stock.transaction.item')
            ->where('status', 'Dibawa')
            ->where('stock_withdrawal_id', $stockWithdrawal->id);
    }

    public function getStockWithdrawalItem(StockWithdrawalItem $stockWithdrawalItem): StockWithdrawalItem
    {
        return $stockWithdrawalItem;
    }
}
