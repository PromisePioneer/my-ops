<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Repository;

use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class StockWithdrawalRepository
{
    public function getStockWithdrawalQuery(): Builder|EloquentBuilder
    {
        return StockWithdrawal::with('branch', 'stocker', 'pic', 'stockWithdrawalByEmployees', 'stockWithdrawalItems');
    }


    public function getStockWithdrawalItems(StockWithdrawal $stockWithdrawal)
    {
        return StockWithdrawalItem::with('stock', 'stock.transaction.item.unitType', 'returnedItem')
            ->where('stock_withdrawal_id', $stockWithdrawal->id);
    }

    public function searchQuery(Builder|ELoquentBuilder $query, string $search): EloquentBuilder|Builder
    {
        $query->whereHas('branch', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('stocker', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('pic', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        return $query;
    }
}
