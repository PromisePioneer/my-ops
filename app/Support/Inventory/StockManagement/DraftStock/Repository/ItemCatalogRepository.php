<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Repository;

use AllowDynamicProperties;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class ItemCatalogRepository
{

    public function __construct()
    {
        return $this->itemCatalog = new ItemCatalog();
    }


    public function getLatestItem(Request $request, int $itemId): Builder
    {
        return ItemCatalog::with('stock.transaction.item')
            ->where(function ($query) use ($itemId) {
                $query->whereHas('stock.transaction.item', function (Builder $query) use ($itemId) {
                    $query->where('id', $itemId);
                });
            })
            ->orderBy('created_at', 'desc');
    }


    public function findByItemId(Request $request, ItemCollection $itemCollection)
    {
        $branch = Branch::with('children')->where('id', $request->user()->branch_id)->first();
        return ItemCatalog::with('stock.transaction.item')
            ->where(function (Builder $query) use ($itemCollection, $request, $branch) {
                $query->whereHas('stock.transaction.item', function (Builder $query) use ($itemCollection, $request, $branch) {
                    if (!empty($request->user()->branch_id)) {
                        $query->whereIn('branch_id', $branch->children->pluck('id'));
                    }
                    $query->where('id', $itemCollection->id);
                });
            });
    }


    public function findByCode(?string $code): Builder
    {
        return $this->itemCatalog->query()->with('stock')->where('code', $code);
    }
}
