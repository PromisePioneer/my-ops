<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use Illuminate\Database\Eloquent\Builder;

class ItemCatalogRepository
{
    public function findByDraftStock(DraftStock $draftStock): Builder
    {
        return ItemCatalog::with('transaction', 'transaction.item', 'createdBy', 'initialInventoryBalance', 'initialInventoryBalance.item')
            ->where('draft_stock_id', $draftStock->id);
    }


    public function getLatestItem(int $itemId): Builder
    {
        return ItemCatalog::with('stock.transaction', 'stock.initialInventoryBalance')
            ->where(function ($query) use ($itemId) {
                $query->whereHas('stock.transaction.item', function (Builder $query) use ($itemId) {
                    $query->where('item_id', $itemId);
                })->orWhereHas('stock.initialInventoryBalance', function (Builder $query) use ($itemId) {
                    $query->where('item_id', $itemId);
                });
            })
            ->orderBy('created_at', 'desc');
    }


    public function findByItemId(ItemCollection $itemCollection)
    {
        return ItemCatalog::with('stock.transaction', 'stock.initialInventoryBalance')
            ->where(function (Builder $query) use ($itemCollection) {
                $query->whereHas('stock.transaction', fn($query) => $query->where('item_id', $itemCollection->id))
                    ->orWhereHas('stock.initialInventoryBalance', fn($query) => $query->where('item_id', $itemCollection->id));
            });
    }
}
