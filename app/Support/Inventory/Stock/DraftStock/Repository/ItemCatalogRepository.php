<?php

namespace App\Support\Inventory\Stock\DraftStock\Repository;

use App\Models\ItemCatalog;
use Illuminate\Database\Eloquent\Builder;

class ItemCatalogRepository
{
    public function getItemCatalogByDraftStockId(): Builder
    {
        return ItemCatalog::with('transaction', 'transaction.item', 'createdBy', 'initialInventoryBalance', 'initialInventoryBalance.item');
    }
}
