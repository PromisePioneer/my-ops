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
        return Stock::where('transaction_id', $draftStock->transaction_id)->first();
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


    public function findByItemId(ItemCollection $itemCollection)
    {
        return Stock::with('transaction', 'initialInventoryBalance')
            ->where(function ($query) use ($itemCollection) {
                $query->whereHas('transaction', function ($query) use ($itemCollection) {
                    $query->where('item_id', $itemCollection->id);
                })->orWhereHas('initialInventoryBalance', function ($query) use ($itemCollection) {
                    $query->where('item_id', $itemCollection->id);
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
        $itemCategory = ItemCategory::find($categoryId);
        if ($itemCategory->name !== 'Kategori 4') {
            $stock = Stock::with('transaction.item', 'initialInventoryBalance.item', 'itemCatalog')
                ->where('branch_id', $branchId)
                ->where(function ($query) use ($categoryId) {
                    $query->whereHas('transaction.item.category', function ($query) use ($categoryId) {
                        $query->where('id', $categoryId);
                    })->orWhereHas('initialInventoryBalance.item.category', function ($query) use ($categoryId) {
                        $query->where('id', $categoryId);
                    });
                })->first();

            if (!empty($stock)) {
                return $stock->itemCatalog()->where(function ($query) use ($branchId) {
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
                    $query->whereNotIn('code', $code)->whereNotIn('code', $code2)->where('available_qty', '>', 0);
                });
            }
        }

        return Stock::with('transaction.item', 'initialInventoryBalance.item', 'itemCatalog')
            ->where('available_qty', '>', 0)
            ->where('branch_id', $branchId)
            ->where(function ($query) use ($categoryId) {
                $query->whereHas('transaction.item.category', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                })->orWhereHas('initialInventoryBalance.item.category', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                });
            });
    }


    public function findByTransactionIdAndBranch(?int $transactionId, ?int $initialInventoryBalanceId, int $branchId): Builder
    {
        return $this->stock->query()
            ->where(function ($query) use ($transactionId, $initialInventoryBalanceId) {
                if ($transactionId) {
                    $query->whereHas('transaction', function ($query) use ($transactionId) {
                        $query->where('id', $transactionId);
                    });
                }
                if ($initialInventoryBalanceId) {
                    $query->whereHas('transaction', function ($query) use ($transactionId) {
                        $query->where('id', $transactionId);
                    });
                }
            })->where('branch_id', $branchId);
    }

    public function findByStockIdAndBranchId(int $stockId, int $branchId): Builder
    {
        return $this->stock->query()->where('id', $stockId)->where('branch_id', $branchId);
    }
}
