<?php

namespace App\Support\Inventory\DraftStock\Repository;

use App\Models\DraftStock;
use Illuminate\Database\Eloquent\Builder;

class DraftStockServiceRepository
{
    public function getDraftStockQuery(): Builder
    {
        return DraftStock::with('transaction.item.category', 'initialInventoryBalance.item.category')
            ->orWherehas('initialInventoryBalance.item.category', function ($query) {
                $query->where('name', '!=', 'Kategori 4');
            })
            ->orWherehas('transaction.item.category', function ($query) {
                $query->where('name', '!=', 'Kategori 4');
            })->where('qty', '>', 0)
            ->orderBy('created_at');
    }
}
