<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Repository;

use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class StockWithdrawalItemRepository
{
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
        return StockWithdrawalItem::with('stockWithdrawal', 'stockWithdrawal.branch', 'stock', 'stock.item')
            ->orderByRaw("FIELD(status , 'Dibawa', 'Dikembalikan', 'Terpakai', 'Habis') ASC");
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

}
