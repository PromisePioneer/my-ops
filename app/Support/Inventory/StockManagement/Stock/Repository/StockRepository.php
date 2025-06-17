<?php

namespace App\Support\Inventory\StockManagement\Stock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class StockRepository
{
    public function findByDraftStock(DraftStock $draftStock)
    {
        return Stock::where('transaction_id', $draftStock->transaction_id)->first();
    }


    public function findByItemCatalog(ItemCatalog $itemCatalog)
    {
        return Stock::where('draft_stock_id', $itemCatalog->draft_stock_id)
            ->where('condition', $itemCatalog->condition)
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


    public static function getSumStockQtyByItemId($itemId)
    {
        return Stock::leftJoin('transactions', 'transactions.id', 'stocks.transaction_id')
            ->leftJoin('initial_inventory_balance', 'initial_inventory_balance.id', 'stocks.initial_balance_inventory_id')
            ->when(!empty(Auth::user()->branch_id), function ($query) {
                $query->where('stocks.branch_id', Auth::user()->branch_id);
            })
            ->where('transactions.item_id', $itemId)
            ->orWhere('transactions.item_id', $itemId)
            ->sum('stocks.available_qty');
    }


    public static function getSumBrokenQty($itemId)
    {
        return Stock::leftJoin('transactions', 'transactions.id', 'stocks.transaction_id')
            ->leftJoin('initial_inventory_balance', 'initial_inventory_balance.id', 'stocks.initial_balance_inventory_id')
            ->when(!empty(Auth::user()->branch_id), function ($query) {
                $query->where('stocks.branch_id', Auth::user()->branch_id);
            })
            ->where('transactions.item_id', $itemId)
            ->orWhere('transactions.item_id', $itemId)
            ->sum('stocks.broken_qty');
    }


    public static function getSumOnHoldQty($itemId)
    {
        return Stock::leftJoin('transactions', 'transactions.id', 'stocks.transaction_id')
            ->leftJoin('initial_inventory_balance', 'initial_inventory_balance.id', 'stocks.initial_balance_inventory_id')
            ->where('transactions.item_id', $itemId)
            ->orWhere('transactions.item_id', $itemId)
            ->sum('stocks.on_hold_qty');
    }

    public function getStockByCategoryAndBranch(int|string $branchId, int|string $categoryId)
    {
        $itemCategory = ItemCategory::find($categoryId);
        if ($itemCategory->name !== 'Kategori 4') {
            return Stock::with('transaction.item', 'initialInventoryBalance.item', 'itemCatalog')
                ->where('branch_id', $branchId)
                ->where(function ($query) use ($categoryId) {
                    $query->whereHas('transaction.item.category', function ($query) use ($categoryId) {
                        $query->where('id', $categoryId);
                    })->orWhereHas('initialInventoryBalance.item.category', function ($query) use ($categoryId) {
                        $query->where('id', $categoryId);
                    });
                });
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
}
