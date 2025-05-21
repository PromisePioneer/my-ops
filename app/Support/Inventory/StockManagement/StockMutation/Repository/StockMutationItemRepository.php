<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Repository;

use App\Models\StockMutationItem;

class StockMutationItemRepository
{
    public function getByStockMutationId(int $stockMutationId)
    {
        return StockMutationItem::with('stockMutation', 'stock')
            ->where('stock_mutation_id', $stockMutationId);
    }
}
