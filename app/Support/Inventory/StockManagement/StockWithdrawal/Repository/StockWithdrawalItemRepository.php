<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Repository;

use AllowDynamicProperties;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

#[AllowDynamicProperties] class StockWithdrawalItemRepository
{
    public function __construct()
    {
        $this->stockWithdrawalItem = new StockWithdrawalItem();
    }


    public function carriedStockCount()
    {
        return StockWithdrawalItem::doesntHave('returnedItem')->sum('qty');
    }

    public function findByStockWithdrawal(StockWithdrawal $stockWithdrawal)
    {
        return StockWithdrawalItem::with('stock', 'stock.itemCatalog')
            ->where('stock_withdrawal_id', $stockWithdrawal->id);
    }


    public function getCarriedStock(): EloquentBuilder
    {
        return StockWithdrawalItem::with(['stockWithdrawal', 'stockWithdrawal.branch', 'stock.transaction.item'])
            ->doesntHave('returnedItem');
    }


    public function search(EloquentBuilder|Builder $query, $search): EloquentBuilder|Builder
    {
        $query->whereHas('stock.item', function (EloquentBuilder|Builder $query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        return $query;
    }

    public function getConsumedOrAppliedStock(StockWithdrawal $stockWithdrawal): EloquentBuilder
    {
        return StockWithdrawalItem::with('stock', 'stock.transaction.item.category')
            ->where('stock_withdrawal_id', $stockWithdrawal->id)
            ->whereIn('status', ['Habis', 'Terpakai', 'Dikembalikan']);
    }


    public function findByStockIdAndCode(int $stockId, int $code)
    {
        return $this->stockWithdrawalItem->query()
            ->with('stock.transaction.item', 'stock.initialInventoryBalance.item')
            ->where('code', $code)
            ->where('stock_id', $stockId);
    }


    public function findByStockId(int $stockId)
    {
        return $this->stockWithdrawalItem->query()
            ->with('stock.transaction.item', 'stock.initialInventoryBalance.item')
            ->where('stock_id', $stockId);
    }

}
