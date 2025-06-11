<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;
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
        return ItemCatalog::with('transaction.branch.parent', 'transaction.item', 'initialInventoryBalance.branch.parent', 'initialInventoryBalance.item', 'draftStock')
            ->where('item_id', $itemId)
            ->orderBy('created_at', 'desc');
    }
}
