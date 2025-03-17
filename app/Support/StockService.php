<?php

namespace App\Support;

use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StockService
{
    private static int $perPage = 10;

    public function goodsData(): LengthAwarePaginator
    {
        $goods = Goods::with('goodsStock', 'unitType')->paginate(self::$perPage);
        return self::formattedGoodsData($goods);
    }


    public function searchGoodsData(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $goods = Goods::with('goodsStock')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);
        return self::formattedGoodsData($goods);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $branchId = $request->branch_id;
        $warehouseId = $request->warehouse_id;
        $data = Goods::with('goodsStock', 'goodsStock.po');

        if ($branchId) {
            $data->with('goodsStock', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        }

        if ($warehouseId) {
            $data->with('goodsStock', function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            });
        }

        return self::formattedGoodsData($data->paginate(self::$perPage));
    }


    private static function formattedGoodsData(LengthAwarePaginator $goodsData): LengthAwarePaginator
    {
        $data = $goodsData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'total_stock' => $item->goodsStock->sum('qty'),
                'category_name' => $item->category->name,
                'name' => $item->name,
                'unit_name' => $item->unitType->name
            ];
        });

        $goodsData->setCollection($data);
        return $goodsData;
    }
}
