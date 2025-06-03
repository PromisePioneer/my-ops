<?php

namespace App\Support\Inventory\StockManagement\Stock\Service;

use AllowDynamicProperties;
use App\Support\Master\Operational\ItemCollections\Repositories\ItemCollectionRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

#[AllowDynamicProperties] class MustReorderStockService
{


    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCollectionRepository = new ItemCollectionRepository();
    }


    public function data(): LengthAwarePaginator
    {
        $stocks = $this->itemCollectionRepository->getMustReorderItem()->paginate(self::$perPage);

        return self::formattedData($stocks);
    }


    public function search(Request $request)
    {

    }

    public function filter()
    {

    }


    public function formattedData(LengthAwarePaginator $stocks): LengthAwarePaginator
    {
        $data = $stocks->getCollection()->map(function ($item) {
            if (Auth::user()->branch_id) {
                $stock = $item->stock->where('branch_id', Auth::user()->branch_id)->sum('qty');
                $draftStock = $item->draftStock->where('branch_id', Auth::user()->branch_id)->sum('qty');
                $totalStock = $stock + $draftStock;
            } else {
                $stock = $item->stock->sum('qty');
            }

            if ($stock < $item->reorder_level) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'total_stock' => $stock,
                    'category_name' => $item->category->name,
                    'name' => $item->name,
                    'unit_name' => $item->unitType->name
                ];
            }
        });

        $stocks->setCollection($data);
        return $stocks;
    }
}
