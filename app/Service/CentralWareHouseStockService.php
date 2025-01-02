<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use Carbon\Carbon;

class CentralWareHouseStockService
{

    public function generateItemCode(CentralWarehouseItem $centralWarehouseItem): string
    {
        $centralWarehouseLatestStock = CentralWarehouseStock::with('centralWarehouseItem.item', 'centralWarehouseItem.warehouse')
            ->where('central_warehouse_item_id', $centralWarehouseItem->id)
            ->latest()
            ->first();
        $dateIn = Carbon::parse($centralWarehouseItem->date)->format('my');

        if ($centralWarehouseLatestStock) {
            $convertCodeToArray = explode('.', $centralWarehouseLatestStock->code);
            $startingNumber = end($convertCodeToArray);
            $startValue = str_pad((int)$startingNumber + 1, 2, '0', STR_PAD_LEFT);

            return $dateIn . '.' . $centralWarehouseItem->item->name . '.' . $centralWarehouseItem->warehouse->code . '.' . $startValue;
        }

        $startingNumber = '00';
        $startValue = str_pad((int)$startingNumber + 1, 2, '0', STR_PAD_LEFT);

        return $dateIn . '.' . $centralWarehouseItem->item->name . '.' . $centralWarehouseItem->warehouse->code . '.' . $startValue;
    }
}
