<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Repository;

use AllowDynamicProperties;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class ItemCatalogRepository
{

    public function __construct()
    {
        return $this->itemCatalog = new ItemCatalog();
    }


    public function getLatestItem(int $itemId): Builder
    {
        return ItemCatalog::with('stock.transaction', 'stock.initialInventoryBalance')
            ->where(function ($query) use ($itemId) {
                $query->whereHas('stock.transaction.item', function (Builder $query) use ($itemId) {
                    $query->where('item_id', $itemId);
                })->orWhereHas('stock.initialInventoryBalance', function (Builder $query) use ($itemId) {
                    $query->where('item_id', $itemId);
                });
            })
            ->orderBy('created_at', 'desc');
    }


    public function findByItemId(ItemCollection $itemCollection)
    {
        return ItemCatalog::with(['stock.transaction', 'stock.initialInventoryBalance'])
            ->where(function (Builder $query) use ($itemCollection) {
                $query->whereHas('stock.transaction.item', function (Builder $query) use ($itemCollection) {
                    $query->where('id', $itemCollection->id);
                })
                    ->orWhereHas('stock.initialInventoryBalance');
            });
    }


    public function findByCode(?string $code): Builder
    {
        return $this->itemCatalog->query()->with('stock')->where('code', $code);
    }
}
