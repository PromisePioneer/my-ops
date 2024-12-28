<?php

namespace App\Service;

use App\Models\CentralWarehouseStock;
use App\Models\PoListOfItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CentralWareHouseStockService
{
    private static int $perPage = 10;

    public function generateSN(POListOfItem $poListOfItem): string
    {
        $latestPo = CentralWarehouseStock::whereHas('po', function ($query) use ($poListOfItem) {
            $query->where('supplier_id', $poListOfItem->supplier_id);
        })->latest()->first();
        $poDate = Carbon::parse($poListOfItem->due_date)->format('dmYHis');

        if ($latestPo) {
            $convertInvNumberToArray = explode('-', $latestPo->sn);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '-' . 'PST' . '-' . $poDate;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '-' . 'PST' . '-' . $poDate;
    }


    public function query(): Builder
    {
        return CentralWareHouseStock::with('po', 'item');
    }


    public function data(): LengthAwarePaginator
    {
        return $this->query()->paginate(self::$perPage);
    }


}
