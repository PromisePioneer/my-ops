<?php

namespace App\Service;

use App\Models\GoodsTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GoodsTransactionService
{
    private static int $perPage = 10;

    public function query(): Builder
    {
        return GoodsTransaction::with(
            'po', 'item', 'toWarehouse', 'fromWarehouse',
            'toBranch', 'fromBranch', 'po.sendBy', 'po.receivedBy')
            ->orderBy('id', 'desc');
    }

    public function data(): LengthAwarePaginator
    {
        $goodsTransaction = $this->query()->paginate(self::$perPage);
        return self::formattedData($goodsTransaction);
    }

    private static function formattedData(LengthAwarePaginator $goodsTransaction): LengthAwarePaginator
    {
        $data = $goodsTransaction->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'po_number' => $item->po->po_number,
                'item_name' => $item->item->name,
                'from' => $item->fromWarehouse?->name ?? $item->fromBranch?->name ?? 'PO',
                'to' => $item->toBranch?->name ?? $item->toWarehouse?->name,
                'sent_by' => $item->po->sendBy?->name,
                'received_by' => $item->po->receivedBy?->name,
                'qty' => $item->qty,
                'status' => $item->po->status
            ];
        });

        $goodsTransaction->setCollection($data);
        return $goodsTransaction;
    }
}
