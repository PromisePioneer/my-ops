<?php

namespace App\Support\Inventory\Stock\DraftStock\Repository;

use App\Models\DraftStock;
use Illuminate\Database\Eloquent\Builder;

class DraftStockServiceRepository
{
    public function getDraftStockQuery(): Builder
    {
        return DraftStock::with('transaction.item.category')
            ->wherehas('transaction.item.category', function ($query) {
                $query->where('name', '!=', 'Kategori 4');
            })->where('qty', '>', 0)
            ->orderBy('created_at');
    }
}
