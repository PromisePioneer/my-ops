<?php

namespace App\Support\Inventory\DraftStock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;
use Illuminate\Database\Eloquent\Builder;

class ItemCatalogRepository
{
    public function findByDraftStock(DraftStock $draftStock): Builder
    {
        return ItemCatalog::with('transaction', 'transaction.item', 'createdBy', 'initialInventoryBalance', 'initialInventoryBalance.item')->where('transaction_id', $draftStock->transaction_id)->orWhere('initial_balance_inventory_id', $draftStock->initial_balance_inventory_id);
    }
}
