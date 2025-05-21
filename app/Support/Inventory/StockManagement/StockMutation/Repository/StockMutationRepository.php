<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Repository;

use App\Models\StockMutation;
use Illuminate\Database\Eloquent\Builder;

class StockMutationRepository
{
    public function getData(): Builder
    {
        return StockMutation::with('oldBranch', 'newBranch', 'item', 'sender', 'receiver');
    }
}
