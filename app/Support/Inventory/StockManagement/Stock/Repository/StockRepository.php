<?php

namespace App\Support\Inventory\StockManagement\Stock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use App\Models\Stock;

class StockRepository
{
    public function findByDraftStock(DraftStock $draftStock)
    {
        return Stock::where('transaction_id', $draftStock->transaction_id)->first();
    }


    public function findByItemCatalog(ItemCatalog $itemCatalog)
    {
        return Stock::where('draft_stock_id', $itemCatalog->draft_stock_id)
            ->where('condition', $itemCatalog->condition)
            ->lockForUpdate()
            ->first();
    }


    public function findByItemAndBranch(int $branchId, int $itemId)
    {

        $itemCollection = ItemCollection::find($itemId);
        if ($itemCollection->must_have_code === 1 || $itemCollection->is_code_listed === 1) {
            return ItemCatalog::with('stock', 'stock.branch', 'stock.item.category', 'stock.item.unitType')
                ->where('item_id', $itemId)->whereHas('stock.branch', function ($query) use ($branchId) {
                    $query->where('parent_id', $branchId);
                });
        }


        return Stock::with('branch', 'item.category', 'item.unitType', 'itemCatalog')
            ->where('item_id', $itemId)
            ->whereHas('branch', function ($query) use ($branchId) {
                $query->where('parent_id', $branchId);
            });
    }


    public function findByItemId(ItemCollection $itemCollection)
    {
        return Stock::with('transaction', 'initialInventoryBalance')
            ->where(function ($query) use ($itemCollection) {
                $query->whereHas('transaction', function ($query) use ($itemCollection) {
                    $query->where('item_id', $itemCollection->id);
                })->orWhereHas('initialInventoryBalance', function ($query) use ($itemCollection) {
                    $query->where('item_id', $itemCollection->id);
                });
            });
    }
}
