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


    private static function formattedGoodsData(LengthAwarePaginator $goodsData): LengthAwarePaginator
    {
        $data = $goodsData->getCollection()->map(function ($item) {

            return [
                'id' => $item->id,
                'verified_stock' => $item->need_sn === 1
                    ? $item->goodsStock->whereNotNull('sn')->where('status', 1)->count()
                    : $item->goodsStock->whereNull('sn')->where('status', 1)->sum('qty'),
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
