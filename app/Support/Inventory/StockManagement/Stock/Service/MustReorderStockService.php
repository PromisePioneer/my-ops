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
        return $stocks->map(function ($item) {
            $totalStock = 0;
            $stock = Stock::find($item->id);
            if (empty($stock)) {
                $stock = 0;
            } else {
                if (!empty(Auth::user()->branch_id)) {
                    $totalStock = $stock->where('branch_id', Auth::user()->branch_id)->sum('qty')
                        + $stock->draftStock->where('branch_id', Auth::user()->branch_id)->sum('qty');
                } else {
                    $totalStock = $stock->sum('qty') + $stock->draftStock->sum('qty');
                }
            }

            if ($totalStock < $item->reorder_level) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'total_stock' => $stock,
                    'category_name' => $item->category->name,
                    'name' => $item->name,
                    'unit_name' => $item->unitType->name
                ];
            }


            return null;
        })->filter(function ($item) {
            return $item !== null;
        })->values();
    }
}
