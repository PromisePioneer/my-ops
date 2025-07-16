<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Repository;

use AllowDynamicProperties;
use App\Models\StockMutation;
use App\Models\StockMutationItem;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class StockMutationRepository
{
    public function __construct()
    {
        $this->stockMutationItem = new StockMutationItem();
    }

    public function getData(): Builder
    {
        return StockMutation::with('oldBranch', 'newBranch', 'sender', 'receiver');
    }
}
