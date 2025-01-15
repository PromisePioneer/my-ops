<?php

namespace App\Service;

use App\Models\Goods;
use App\Models\GoodsPurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GoodsStockService
{
    private static int $perPage = 10;

    public function goodsData(): LengthAwarePaginator
    {
        $goods = Goods::with('goodsStock')->paginate(self::$perPage);
        return self::formattedGoodsData($goods);
    }


    public function searchGoodsData(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $goods = Goods::with('goodsStock')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(self::$perPage);
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
            if ($item->need_sn === 1 || $item->already_has_sn_on_item === 1) {
                $stock = $item->goodsStock->whereNotNull('sn')->where('status', 1)->count();
            } else {
                $stock = $item->goodsStock->whereNull('sn')->where('status', 1)->sum('qty');
            }

            return [
                'id' => $item->id,
                'verified_stock' => $stock,
                'unverified_stock' => $item->need_sn === 1
                    ? $item->goodsStock->whereNotNull('sn')->where('status', 0)->count()
                    : $item->goodsStock->whereNull('sn')->where('status', 0)->sum('qty'),
                'name' => $item->name
            ];
        });

        $goodsData->setCollection($data);
        return $goodsData;
    }


    public function getPO(Goods $goods): LengthAwarePaginator
    {
        return GoodsPurchaseOrder::with('warehouse', 'branch')
            ->where('status', 1)
            ->where('item_id', $goods->id)
            ->paginate(self::$perPage);

    }


    public function searchPO(Request $request, Goods $goods)
    {
        $search = $request->input('search');
        return GoodsPurchaseOrder::where('status', 1)->where('item_id', $goods->id)->when(!empty($search), function ($query) use ($search) {
            $query->where('po_number', 'like', '%' . $search . '%')
                ->orWhere('qty', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);
    }
}
