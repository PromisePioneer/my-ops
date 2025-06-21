<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Repository;

use AllowDynamicProperties;
use App\Models\StockMutationItem;

#[AllowDynamicProperties] class StockMutationItemRepository
{
    public function __construct()
    {
        $this->stockMutationItem = new StockMutationItem();
    }


    public function getByStockMutationId(int $stockMutationId)
    {
        return $this->stockMutationItem->with('stockMutation', 'stock')
            ->where('stock_mutation_id', $stockMutationId);
    }
}
