<?php

namespace App\Support\Master\Operational\InitialInventoryBalance\Repository;

use App\Models\InitialInventoryBalance;
use Illuminate\Database\Eloquent\Builder;

class InitialInventoryBalanceRepository
{
    public function data(): Builder
    {
        return InitialInventoryBalance::with('branch', 'supplier', 'item', 'branch.parent', 'stockAccount');
    }


    public function search(string $search): Builder
    {
        return $this->data()->whereHas('branch', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('supplier', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('itemCollection', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }
}
