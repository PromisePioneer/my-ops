<?php

namespace App\Support\Inventory\StockManagement\MustReorderStock\Service;

use AllowDynamicProperties;
use App\Support\Inventory\StockManagement\DraftStock\Repository\DraftStockRepository;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
use App\Support\Master\Operational\ItemCollections\Repositories\ItemCollectionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

#[AllowDynamicProperties] class MustReorderStockService
{


    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCollectionRepository = new ItemCollectionRepository();
    }


    public function data(Request $request): Collection
    {
        $stocks = $this->itemCollectionRepository->getMustReorderItem()->get();
        return self::formattedData($request, $stocks);
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $query = $this->itemCollectionRepository->getMustReorderItem();
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return self::formattedData($request, $query->get());
    }

    public function filter(Request $request)
    {
        $stocks = $this->itemCollectionRepository->getMustReorderItem()->get();
        return self::formattedData($request, $stocks);
    }


    public function formattedData(Request $request, $stocks)
    {
        return $stocks->map(function ($item) use ($request) {
            $totalDraftStockQty = DraftStockRepository::draftStockQtySumByItemId($request, $item->id);
            $stockQty = StockRepository::getSumStockQtyByItemId($request, $item->id)->sum('available_qty');
            return [
                'id' => $item->id,
                'type' => $item->type,
                'category_name' => $item->category?->name,
                'name' => $item->name,
                'unit_name' => $item->unitType->name,
                'reorder_level' => $item->reorder_level,
                'total_stock' => $totalDraftStockQty + $stockQty,
            ];
        })->filter(function ($item) use ($request) {
            $totalDraftStockQty = DraftStockRepository::draftStockQtySumByItemId($request, $item['id']);
            $stockQty = StockRepository::getSumStockQtyByItemId($request, $item['id'])->sum('available_qty');
            $totalStock = $totalDraftStockQty + $stockQty;
            return $totalStock < $item['reorder_level'];
        })->values();
    }

}
