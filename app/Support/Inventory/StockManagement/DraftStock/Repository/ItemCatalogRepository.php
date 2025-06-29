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
        return ItemCatalog::with(['stock.transaction.item', 'stock.initialInventoryBalance.item'])
            ->where(function ($query) use ($itemId) {
                $query->whereHas('stock.transaction.item', function (Builder $query) use ($itemId) {
                    $query->where('id', $itemId);
                })->orWhereHas('stock.initialInventoryBalance.item', function (Builder $query) use ($itemId) {
                    $query->where('id', $itemId);
                });
            })
            ->orderBy('created_at', 'desc');
    }


    public function findByItemId(ItemCollection $itemCollection)
    {
        return ItemCatalog::with(['stock.transaction.item', 'stock.initialInventoryBalance.item'])
            ->where(function (Builder $query) use ($itemCollection) {
                $query->whereHas('stock.transaction.item', function (Builder $query) use ($itemCollection) {
                    $query->where('id', $itemCollection->id);
                })->orWhereHas('stock.initialInventoryBalance.item', function ($query) use ($itemCollection) {
                    $query->where('id', $itemCollection->id);
                });
            });
    }


    public function findByCode(?string $code): Builder
    {
        return $this->itemCatalog->query()->with('stock')->where('code', $code);
    }
}
