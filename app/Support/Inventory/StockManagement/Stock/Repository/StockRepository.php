<?php

namespace App\Support\Inventory\StockManagement\Stock\Repository;

use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockRepository
{
    public function findByDraftStock(DraftStock $draftStock, $request)
    {
        return Stock::where('draft_stock_id', $draftStock->id)
            ->where('condition', $request->input('condition'))
            ->first();
    }


    public function findByItemCatalog(ItemCatalog $itemCatalog)
    {
        return Stock::where('draft_stock_id', $itemCatalog->draft_stock_id)
            ->where('condition', $itemCatalog->condition)
            ->lockForUpdate()
            ->first();
    }


    public function findByItemAndBranch(int $itemId, int $branchId)
    {
        return Stock::with('branch', 'item.category', 'item.unitType', 'itemCatalog')
            ->where('item_id', $itemId)
            ->whereHas('branch', function ($query) use ($branchId) {
                $query->where('parent_id', $branchId);
            });
    }


    public function findByDraftStockAndItemName(DraftStock $draftStock)
    {
        return Stock::with('transaction', 'branch', 'item')
            ->whereHas('item', function ($query) use ($draftStock) {
                $query->where('name', $draftStock->transaction->item->name ?? $draftStock->initialInventoryBalance->item->name);
            })->where('draft_stock_id', $draftStock->id);
    }


    public function getStockWithCodes()
    {
        return Stock::with('item', 'itemCatalog')
            ->whereHas('item.category', function ($query) {
                $query->where('name', '!=', 'Kategori 4');
            })->when(!empty(Auth::user()->branch_id), function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->where('condition', 'Baik');
    }


    public function getStockWithoutCode(Request $request)
    {
        return Stock::with('item')->whereHas('item.category', function ($query) {
            $query->where('name', 'Kategori 4');
        })->when(!empty(Auth::user()->branch_id), function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->whereNotIn('id', $request->get('ids', []))
            ->whereIn('condition', ['Baik', 'Diperbaiki']);
    }
}
