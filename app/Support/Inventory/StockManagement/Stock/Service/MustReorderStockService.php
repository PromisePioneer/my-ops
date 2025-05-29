<?php

namespace App\Support\Inventory\StockManagement\Stock\Service;

use AllowDynamicProperties;
use App\Models\Stock;
use App\Support\Master\Operational\ItemCollections\Repositories\ItemCollectionRepository;
use Auth;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class MustReorderStockService
{


    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCollectionRepository = new ItemCollectionRepository();
    }


    public function data()
    {

        $stocks = $this->itemCollectionRepository->getMustReorderItem()->get();
        return self::formattedData($stocks);
    }


    public function search(Request $request)
    {

    }

    public function filter()
    {

    }


    public function formattedData($stocks)
    {
        $branchId = Auth::user()->branch_id;

        // Ambil semua stok yang terkait dengan item dalam $stocks
        $stockData = Stock::whereIn('item_id', $stocks->pluck('id'))
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->selectRaw('item_id, SUM(qty) as total_qty')
            ->groupBy('item_id')
            ->pluck('total_qty', 'item_id');

        return $stocks->map(function ($item) use ($stockData) {
            $totalStock = $stockData[$item->id] ?? 0;

            if ($totalStock < $item->reorder_level) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'total_stock' => $totalStock,
                    'category_name' => $item->category->name,
                    'name' => $item->name,
                    'unit_name' => $item->unitType->name
                ];
            }

            return null;
        })->filter()->values();
    }

}
