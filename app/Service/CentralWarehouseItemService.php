<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CentralWarehouseItemService
{

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        return CentralWarehouseItem::with('item', 'warehouse', 'po', 'unitType')->withCount('centralWarehouseStock')->paginate(self::$perPage);
    }
}
