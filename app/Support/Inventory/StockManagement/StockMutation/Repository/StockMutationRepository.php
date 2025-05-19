<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Repository;

use App\Models\StockMutationHistory;
use Illuminate\Database\Eloquent\Builder;

class StockMutationRepository
{
    public function getData(): Builder
    {
        return StockMutationHistory::with('oldBranch', 'newBranch', 'item', 'stocker');
    }
}
