<?php

namespace App\Support;

use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
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
                'total_stock' => $stock,
                'category_name' => $item->category->name,
                'name' => $item->name,
                'unit_name' => $item->unitType->name
            ];
        });

        $goodsData->setCollection($data);
        return $goodsData;
    }

}
