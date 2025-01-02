<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CentralWareHouseStockService
{

    public function generateCodeWithNumber(CentralWarehouseItem $centralWarehouseItem, $number): string
    {
        $itemName = $centralWarehouseItem->item->name ?? 'UnknownItem';
        $warehouseCode = $centralWarehouseItem->warehouse->code ?? 'UnknownWarehouse';
        $itemSlug = Str::slug($itemName, '');
        $warehouseSlug = Str::slug($warehouseCode, '');
        $dateIn = Carbon::parse($centralWarehouseItem->date)->format('my');
        $paddedNumber = str_pad($number + 1, 2, '0', STR_PAD_LEFT);

        return $dateIn . "." . strtoupper($itemSlug) . "." . strtoupper($warehouseSlug) . "." . $paddedNumber;
    }
}
