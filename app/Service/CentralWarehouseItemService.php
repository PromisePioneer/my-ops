<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use App\Models\Goods;
use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CentralWarehouseItemService
{

    private static int $perPage = 10;


    public function query(): Builder
    {
        return CentralWarehouseItem::with('item', 'warehouse', 'po', 'item.unitType');
    }

    public function data(): LengthAwarePaginator
    {
        return $this->query()->paginate(self::$perPage);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        return $this->query()->when(!empty($search), function (Builder $query) use ($search) {
            $query->whereHas('item', function (Builder $query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('warehouse', function (Builder $query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        })->paginate(self::$perPage);
    }


    public function stockData(): LengthAwarePaginator
    {
        $items = Goods::with('centralWarehouseStock')->paginate(self::$perPage);

        return self::formattedStockData($items);
    }


    public function formattedStockData($item)
    {
        $data = $item->getCollection()->map(function ($item) {

            return [
                'id' => $item->id,
                'name' => $item->name,
                'stock' => $item->need_sn === 1
                    ? $item->centralWarehouseStock->where('status', true)->whereNotNull('sn')->count('id')
                    : $item->centralWarehouseStock->where('status', true)->whereNull('sn')->sum('qty'),
            ];
        });

        $item->setCollection($data);
        return $item;
    }


    public function getStockDataDetail(Goods $item)
    {
        $stockDataDetail = Warehouse::with(['centralWarehouseStock' => function ($query) use ($item) {
            $query->where('item_id', $item->id);
        }])->paginate(self::$perPage);

        return self::formattedStockDataDetail($stockDataDetail, $item);
    }


    public function formattedStockDataDetail($stockDataDetail, $item)
    {
        $data = $stockDataDetail->getCollection()->map(function ($value) use ($item) {

            $getItem = Goods::where('id', $item->id)->first();

            return [
                'id' => $value->id,
                'name' => $value->name,
                'stock' => $getItem->need_sn === 1
                    ? $value->centralWarehouseStock->where('status', true)->whereNotNull('sn')->count('id')
                    : $value->centralWarehouseStock->where('status', true)->whereNull('sn')->sum('qty'),
            ];
        });

        $stockDataDetail->setCollection($data);
        return $stockDataDetail;
    }
}
