<?php

namespace App\Http\Controllers;

use App\Models\CentralWarehouseStock;
use App\Models\ItemTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ItemTransactionController extends Controller
{
    private static int $perPage = 10;

    public function data(): JsonResponse
    {
        $data = ItemTransaction::with('po', 'item', 'item.unitType', 'warehouse')->paginate(self::$perPage);
        return response()->json($data);
    }


    public function confirm(ItemTransaction $itemTransaction): JsonResponse
    {
        DB::transaction(function () use ($itemTransaction) {
            $itemTransaction->update([
                'status' => 1
            ]);


            if ($itemTransaction->item->need_sn === 0) {
                CentralWarehouseStock::create([
                    'po_id' => $itemTransaction->po_id,
                    'warehouse_id' => $itemTransaction->warehouse_id,
                    'item_id' => $itemTransaction->item_id,
                    'qty' => $itemTransaction->qty,
                    'status' => 1
                ]);
            }
        });
        return response()->json(['message' => 'barang berhasil diterima']);
    }
}
