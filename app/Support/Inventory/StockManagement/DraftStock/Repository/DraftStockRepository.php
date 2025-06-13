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
        return ItemCollection::with('transaction.draftStock', 'initialInventoryBalance.draftStock')
            ->where('must_have_code', true)
            ->whereHas('transaction.draftStock')
            ->orWhereHas('initialInventoryBalance.draftStock');
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
}
