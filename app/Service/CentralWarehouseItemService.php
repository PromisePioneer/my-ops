<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CentralWarehouseItemService
{

    private static int $perPage = 10;


    public function query(): Builder
    {
        return CentralWarehouseItem::with('item', 'warehouse', 'po', 'item.unitType')->withCount('centralWarehouseStock');
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
        $items = Item::with('centralWarehouseItem.centralWarehouseStock')->paginate(self::$perPage);

        return self::formattedStockData($items);
    }


    public function formattedStockData($item)
    {
        $data = $item->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'stock' => $item->need_sn === 0 ? $item->centralWarehouseItem->map(function ($item) {
                    return [
                        'stock' => $item->centralWarehouseStock->where('status', true)->count('id'),
                    ];
                })->reduce(function ($carry, $item) {
                    return $carry + $item['stock'];
                }) : $item->centralWarehouseItem->sum('qty'),
                'sn_non_verified' => $item->centralWarehouseItem->map(function ($item) {
                    return [
                        'sn_non_verified' => $item->centralWarehouseStock->where('status', false)->count('id'),
                    ];
                })->reduce(function ($carry, $item) {
                    return $carry + $item['sn_non_verified'];
                })
            ];
        });

        $item->setCollection($data);
        return $item;
    }


    public function getStockDataDetail(Item $item)
    {
        $stockDataDetail = Warehouse::with(['centralWarehouseItem' => function ($query) use ($item) {
            $query->where('item_id', $item->id);
        }])->paginate(self::$perPage);

        return self::formattedStockDataDetail($stockDataDetail);
    }


    public function formattedStockDataDetail($stockDataDetail)
    {
        $data = $stockDataDetail->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'stock' => $item->need_sn === 0 ? $item->centraLWarehouseItem->map(function ($item) {
                    return [
                        'stock' => $item->centralWarehouseStock->where('status', true)->count('id'),
                    ];
                })->reduce(function ($carry, $item) {
                    return $carry + $item['stock'];
                }) : $item->centralWarehouseItem->sum('qty'),
            ];
        });

        $stockDataDetail->setCollection($data);
        return $stockDataDetail;
    }
}
