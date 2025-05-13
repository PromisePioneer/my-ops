<?php

namespace App\Support\Inventory\Stock\DraftStock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;
use Illuminate\Database\Eloquent\Builder;

class ItemCatalogRepository
{
    public function findByTransactionIdOrInitialBalanceInventoryId(DraftStock $draftStock): Builder
    {
        return ItemCatalog::with('transaction', 'transaction.item', 'createdBy', 'initialInventoryBalance', 'initialInventoryBalance.item')->where('draft_stock_id', $draftStock->id);
    }
}
