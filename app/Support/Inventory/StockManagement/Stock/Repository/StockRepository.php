<?php

namespace App\Support\Inventory\StockManagement\Stock\Repository;

use AllowDynamicProperties;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class StockRepository
{
    public function __construct()
    {
        $this->stock = new Stock();
    }


    public function findByDraftStock(DraftStock $draftStock): ?Stock
    {
        $draftStock->load('transaction.item');
        return Stock::where(function ($query) use ($draftStock) {
            $query->whereHas('transaction.item', function ($query) use ($draftStock) {
                $query->where('id', $draftStock->transaction?->item->id);
            })->where('transaction_id', $draftStock->transaction_id);
        })->first();
    }


    public function findByItemCatalog(ItemCatalog $itemCatalog): ?Stock
    {
        return Stock::where('id', $itemCatalog->stock?->id)
            ->lockForUpdate()
            ->first();
    }


    public function findByItemAndBranch(int $branchId, int $itemId)
    {

        $itemCollection = ItemCollection::find($itemId);
        if ($itemCollection->must_have_code === 1 || $itemCollection->is_code_listed === 1) {
            return ItemCatalog::with('stock', 'stock.branch', 'stock.item.category', 'stock.item.unitType')
                ->where('item_id', $itemId)->whereHas('stock.branch', function ($query) use ($branchId) {
                    $query->where('parent_id', $branchId);
                });
        }


        return Stock::with('branch', 'item.category', 'item.unitType', 'itemCatalog')
            ->where('item_id', $itemId)
            ->whereHas('branch', function ($query) use ($branchId) {
                $query->where('parent_id', $branchId);
            });
    }


    public function findByItemId(Request $request, ItemCollection $itemCollection)
    {
        $branch = Branch::with('children')->find($request->user()->branch_id);
        return Stock::with('transaction.item')
            ->where(function ($query) use ($itemCollection, $request, $branch) {
                $query->whereHas('transaction.item', function ($query) use ($itemCollection, $request, $branch) {
                    if (!empty($request->user()->branch_id)) {
                        $query->whereIn('branch_id', $branch->children->pluck('id'));
                    }
                    $query->where('id', $itemCollection->id);
                });
            });
    }


    public static function getSumStockQtyByItemId(Request $request, $itemId)
    {
        $branch = Branch::with('children')->find($request->user()->branch_id);
        return Stock::with('transaction', 'initialInventoryBalance')
            ->where(function ($query) use ($itemId, $request, $branch) {
                $query->whereHas('transaction', function ($query) use ($itemId, $request, $branch) {
                    $query->when(!empty($request->user()->branch_id), function ($query) use ($itemId, $request, $branch) {
                        $query->whereIn('stocks.branch_id', $branch->children->pluck('id')->toArray());
                    })->where('item_id', $itemId);
                });
            });
    }

    public function getStockByCategoryAndBranch(int|string $branchId, int|string $categoryId)
    {
        $code = [];
        $code2 = [];
        if (session()->has('stock_withdrawal_item')) {
            foreach (session()->get('stock_withdrawal_item') as $withDrawalItem) {
                $code[] = $withDrawalItem['code'];
            }
        }

        if (session()->has('stock_mutation_items')) {
            foreach (session()->get('stock_mutation_items') as $mutationItem) {
                $code2[] = $mutationItem['code'];
            }
        }
        $itemCategory = ItemCategory::find($categoryId);
        if ($itemCategory->name !== 'Kategori 4') {
            return ItemCatalog::with(['stock.transaction.item', 'stock'])
                ->whereHas('stock.transaction.item', function ($query) use ($branchId, $itemCategory) {
                    $query->where('category_id', $itemCategory->id)
                        ->where('stocks.branch_id', $branchId);
                })->where(function ($query) use ($branchId, $code, $code2) {
                    $query->whereNotIn('code', $code)
                        ->whereNotIn('code', $code2)
                        ->where('available_qty', '>', 0);
                });
        }

        return Stock::with(['transaction.item', 'itemCatalog'])
            ->where('available_qty', '>', 0)
            ->where('branch_id', $branchId)
            ->where(function ($query) use ($categoryId) {
                $query->whereHas('transaction.item.category', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                });
            });
    }


    public function findByTransactionIdAndBranch(?int $transactionId, ?int $initialInventoryBalanceId, int $branchId): Builder
    {
        return $this->stock->query()
            ->where(function ($query) use ($transactionId, $initialInventoryBalanceId) {
                if (!empty($transactionId)) {
                    $query->whereHas('transaction', function ($query) use ($transactionId) {
                        $query->where('id', $transactionId);
                    });
                }
                if (!empty($initialInventoryBalanceId)) {
                    $query->whereHas('initialInventoryBalance', function ($query) use ($initialInventoryBalanceId) {
                        $query->where('id', $initialInventoryBalanceId);
                    });
                }
            })->where('branch_id', $branchId);
    }

    public function findByStockIdAndBranchId(int $stockId, int $branchId): Builder
    {
        return $this->stock->query()->where('id', $stockId)->where('branch_id', $branchId);
    }
}
