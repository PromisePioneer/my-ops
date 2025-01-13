<?php

namespace App\Service;

use App\Models\Branch;
use App\Models\BranchWarehouseItem;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchWarehouseItemService
{

    private static int $perPage = 10;

    public function query(): Builder
    {
        return BranchWarehouseItem::with('po', 'item', 'branch', 'centralWarehouseStock', 'item.unitType');
    }

    public function data(): LengthAwarePaginator
    {
        return $this->query()->paginate(self::$perPage);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        return $this->query()->when(!empty($search), function ($query) use ($search) {
            $query->whereHas('item', function (Builder $query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
            $query->orWwhereHas('po', function (Builder $query) use ($search) {
                $query->where('po_number', 'like', '%' . $search . '%');
            });
        })->paginate(self::$perPage);
    }


    public function stockData()
    {
        $items = Goods::with('branchWarehouseItem.branchWarehouseStock')->paginate(self::$perPage);

        return self::formattedStockData($items);
    }


    public function searchStockData(Request $request)
    {
        $search = $request->input('search');
        $items = Goods::with('branchWarehouseItem.branchWarehouseStock')->when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);

        return self::formattedStockData($items);
    }


    private static function formattedStockData($item)
    {
        $data = $item->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'stock' => $item->branchWarehouseItem->map(function ($item) {
                    return [
                        'stock' => $item->branchWarehouseStock->count('id'),
                    ];
                })->reduce(function ($carry, $item) {
                    return $carry + $item['stock'];
                })
            ];
        });

        $item->setCollection($data);
        return $item;
    }


    public
    function getStockDataDetail(Goods $item)
    {
        $stockDataDetail = Branch::with(['branchWarehouseItem' => function ($query) use ($item) {
            $query->where('item_id', $item->id);
        }])->paginate(self::$perPage);

        return self::formattedStockDataDetail($stockDataDetail);
    }


    private
    static function formattedStockDataDetail($stockDataDetail)
    {
        $data = $stockDataDetail->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'unitType' => $item->branchWarehouseItem->first()?->unitType?->name,
                'stock' => $item->branchWarehouseItem->map(function ($item) {
                    return [
                        'stock' => $item->branchWarehouseStock->count('id'),
                    ];
                })->reduce(function ($carry, $item) {
                    return $carry + $item['stock'];
                })
            ];
        });

        $stockDataDetail->setCollection($data);
        return $stockDataDetail;
    }
}
