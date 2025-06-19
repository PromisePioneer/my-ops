<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class DraftStockRepository
{

    public function getQty(Request $request): int|null
    {
        $branch = Branch::with('children')->where('id', $request->user()->branch_id)->first();


        return DraftStock::with('transaction', 'initialInventoryBalance')
            ->where(function ($query) use ($request, $branch) {
                $query->whereHas('transaction', function ($query) use ($request, $branch) {
                    if (!empty($request->user()->branch_id)) {
                        $query->whereIn('branch_id', $branch->children->pluck('id'));
                    }
                })->orWhereHas('initialInventoryBalance', function ($query) use ($request, $branch) {
                });
            })
            ->sum('qty');
    }

    public function getDraftStockQuery(): EloquentBuilder|Builder
    {
        return ItemCollection::where('is_code_listed', true)->orWhere('must_have_code', true);
    }


    public function getDraftStockByItemId(ItemCollection $itemCollection, Request $request): EloquentBuilder|Builder
    {
        return DraftStock::with('transaction', 'initialInventoryBalance')
            ->where(function ($query) use ($itemCollection, $request) {
                $query->whereHas('transaction', function (EloquentBuilder $query) use ($itemCollection, $request) {
                    $query->when(!empty($request->user()->branch_id), fn($query) => $query->where('branch_id', $request->user()->branch_id))
                        ->where('item_id', $itemCollection->id);
                });
                $query->orWhereHas('initialInventoryBalance', function (EloquentBuilder $query) use ($itemCollection, $request) {
                    $query->when(!empty($request->user()->branch_id), fn($query) => $query->where('branch_id', $request->user()->branch_id))
                        ->where('item_id', $itemCollection->id);
                });
            });
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


    public static function draftStockQtySumByItemId(Request $request, int $itemId)
    {
        $branch = Branch::with('children')->find($request->user()->branch_id ?? $request->input('branch_id'));
        return DraftStock::with('transaction.item', 'initialInventoryBalance')
            ->where(function ($query) use ($request, $itemId, $branch) {
                $query->whereHas('transaction.item', function (EloquentBuilder $query) use ($itemId, $branch, $request) {
                    if (!empty($request->user()->branch_id || $request->input('branch_id'))) {
                        $query->where('id', $itemId)->whereIn('branch_id', $branch->children->pluck('id'));
                    }
                    if (empty($request->user()->branch_id)) {
                        $query->where('id', $itemId);
                    }
                });
            })->sum('qty');
    }
}
