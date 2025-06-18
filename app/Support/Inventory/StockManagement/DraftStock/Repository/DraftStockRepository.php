<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCollection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class DraftStockRepository
{

    public function getQty(): int|null
    {
        return DraftStock::with('transaction')->sum('qty');
    }

    public function getDraftStockQuery(): EloquentBuilder|Builder
    {
        return ItemCollection::where('is_code_listed', true)->orWhere('must_have_code', true);
    }


    public function getDraftStockByItemId(ItemCollection $itemCollection, Request $request): EloquentBuilder|Builder
    {
        return DraftStock::with('transaction', 'initialInventoryBalance')
            ->where(function ($query) use ($itemCollection, $request) {
                $query->whereHas('transaction', function (EloquentBuilder $query) use ($itemCollection, $request) {
                    $query->when(!empty($request->user()->branch_id), fn($query) => $query->where('branch_id', $request->user()->branch_id))
                        ->where('item_id', $itemCollection->id);
                });
                $query->orWhereHas('initialInventoryBalance', function (EloquentBuilder $query) use ($itemCollection, $request) {
                    $query->when(!empty($request->user()->branch_id), fn($query) => $query->where('branch_id', $request->user()->branch_id))
                        ->where('item_id', $itemCollection->id);
                });
            });
    }


    public function searchQuery(Builder|EloquentBuilder $query, string $search): EloquentBuilder|Builder
    {
        $query->whereHas('transaction.item', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('initialInventoryBalance.item', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        return $query;
    }


    public static function draftStockQtySumByItemId($itemId)
    {
        return DraftStock::leftJoin('transactions', 'transactions.id', 'draft_stocks.transaction_id')
            ->leftJoin('initial_inventory_balance', 'initial_inventory_balance.id', 'draft_stocks.initial_balance_inventory_id')
            ->where('initial_inventory_balance.item_id', $itemId)
            ->orWhere('transactions.item_id', $itemId)
            ->sum('draft_stocks.qty');
    }
}
