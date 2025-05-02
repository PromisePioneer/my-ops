<?php

namespace App\Support\Inventory\Stock\DraftStock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;

class ItemCatalogRepository
{
    public function getItemCatalogByDraftStockId(DraftStock $draftStock)
    {
        return ItemCatalog::with('transaction', 'transaction.item', 'createdBy');
    }
}
