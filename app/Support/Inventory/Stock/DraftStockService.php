<?php

namespace App\Support\Inventory\Stock;

use App\Models\ItemCollection;

class DraftStockService
{
    public function getDraftStockQty()
    {
        return ItemCollection::withSum('qty')->get();
    }
}
