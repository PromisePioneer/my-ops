<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Repository;

use App\Models\DraftStock;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class DraftStockRepository
{

    public function getQty(): int|null
    {
        return DraftStock::with('transaction')->sum('qty');
    }

    public function getDraftStockQuery(): Builder|EloquentBuilder
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


    public function searchQuery(Builder|EloquentBuilder $query, string $search): EloquentBuilder|Builder
    {
        $query->whereHas('transaction.item', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('initialInventoryBalance.item', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        return $query;
    }
}
