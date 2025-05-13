<?php

namespace App\Support;

use App\Models\DraftStock;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class StockService
{
    private static int $perPage = 10;

    public function goodsData(): LengthAwarePaginator
    {
        $goods = ItemCollection::with('goodsStock', 'unitType')->paginate(self::$perPage);
        return self::formattedGoodsData($goods);
    }


    public function searchGoodsData(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $goods = ItemCollection::with('goodsStock')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);
        return self::formattedGoodsData($goods);
    }


    public function filter(Request $request): LengthAwarePaginator
    {

        $branch = Branch::find($request->branch_id);
        $warehouseId = $request->warehouse_id;
        $data = ItemCollection::with('goodsStock');

        if ($branch) {
            $data->with('goodsStock', function ($query) use ($branch) {
                $query->where('branch_id', $branch->id);
            });
        }


        return self::formattedGoodsData($data->paginate(self::$perPage));
    }


    private static function formattedGoodsData(LengthAwarePaginator $goodsData): LengthAwarePaginator
    {
        $data = $goodsData->getCollection()->map(function ($item) {

            $stock = 0;
            if (Auth::user()->branch_id) {
                $stock = $item->goodsStock->where('branch_id', Auth::user()->branch_id)->sum('qty');
            } else {
                $stock = $item->goodsStock->sum('qty');
            }

            return [
                'id' => $item->id,
                'type' => $item->type,
                'total_stock' => $stock,
                'category_name' => $item->category->name,
                'name' => $item->name,
                'unit_name' => $item->unitType->name
            ];
        });

        $goodsData->setCollection($data);
        return $goodsData;
    }


    public function getMustReorderStocks(Request $request)
    {
        $itemDoesntHaveGoodsStock = ItemCollection::whereDoesntHave('goodsStock')->count();
        $items = ItemCollection::all();

        foreach ($items as $item) {
            $itemHasGoodsStock = ItemCollection::whereHas('goodsStock', function ($query) use ($item) {
                $query->where('qty', '<', $item->reorder_level);
            })->count();
            $draftStock = DraftStock::whereHas('transaction.item', function ($query) use ($item) {
                $query->where('id', $item->id);
            })->where('qty', '<', $item->reorder_level)->count();
        }

        return $itemDoesntHaveGoodsStock + $itemHasGoodsStock + $draftStock;
    }


    public function getStockBasedOnItemIdAndBranchId($branchId, $itemId)
    {

        $stocks = Stock::with('branch', 'item.category', 'item.unitType', 'itemCatalog')
            ->where('item_id', $itemId)
            ->whereHas('branch', function ($query) use ($branchId) {
                $query->where('parent_id', $branchId);
            })->get();

        return $stocks->flatMap(function ($stock) {
            return $stock->itemCatalog->map(function ($itemCatalog) use ($stock) {
                return [
                    'id' => $itemCatalog->id,
                    'branch_name' => $stock->branch->name,
                    'name' => $stock->item->name,
                    'code' => $itemCatalog->code,
                    'condition' => $itemCatalog->condition,
                ];
            });
        });
    }

}
