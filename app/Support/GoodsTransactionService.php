<?php

namespace App\Support;

use App\Models\GoodsTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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


    public function store(Request $request): void
    {
        DB::transaction(function () use($request) {
            GoodsTransaction::create([
                'date' => $request->date,
                'from_warehouse_id' => Auth::user()->branch_id === null ? $request->warehouse_id : null,
                'from_branch_id' => Auth::user()->branch_id !== null ? $request->branch_id : null,
                'to_warehouse_id' => $request->warehouse_id,
                'to_branch_id' => $request->branch_id,
                'qty' => $request->qty,
                'po_id' => $request->po_id,
            ]);
        });
    }
}
