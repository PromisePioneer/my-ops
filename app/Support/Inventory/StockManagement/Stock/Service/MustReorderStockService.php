<?php

namespace App\Support\Inventory\StockManagement\Stock\Service;

use AllowDynamicProperties;
use App\Support\Inventory\StockManagement\DraftStock\Repository\DraftStockRepository;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
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
        return $stocks->map(function ($item) {
            $totalDraftStockQty = DraftStockRepository::draftStockQtySumByItemId($item->id);
            $stockQty = StockRepository::getSumStockQtyByItemId($item->id);
            return [
                'id' => $item->id,
                'type' => $item->type,
                'category_name' => $item->category?->name,
                'name' => $item->name,
                'unit_name' => $item->unitType->name,
                'reorder_level' => $item->reorder_level,
                'total_stock' => $totalDraftStockQty + $stockQty,
            ];
        })->filter(function ($item) {
            $totalDraftStockQty = DraftStockRepository::draftStockQtySumByItemId($item['id']);
            $stockQty = StockRepository::getSumStockQtyByItemId($item['id']);
            $totalStock = $totalDraftStockQty + $stockQty;
            return $totalStock < $item['reorder_level'];
        })->values();
    }

}
