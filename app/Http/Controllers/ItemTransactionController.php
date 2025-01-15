<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use App\Models\GoodsTransaction;
use App\Service\ItemTransaction\IncomingItemTransactionService;
use App\Service\ItemTransaction\PoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

#[AllowDynamicProperties] class ItemTransactionController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->poService = new PoService();
        $this->incomingItemTransactionService = new IncomingItemTransactionService();
    }

    public function poData(): JsonResponse
    {
        return response()->json($this->poService->data());
    }

    public function poSearch(Request $request): JsonResponse
    {
        return response()->json($this->poService->search($request));
    }

    public function poDetail(GoodsTransaction $itemTransaction): View
    {
        $centralWarehouseItem = CentralWarehouseItem::where('po_id', $itemTransaction->po_id)->first();
        return view('pages.inventory.list-of-items.central-warehouse.item-distribution.detail', compact('itemTransaction', 'centralWarehouseItem'));
    }


    public function poDetailData(GoodsTransaction $itemTransaction): JsonResponse
    {
        $centralWarehouseItem = CentralWarehouseItem::with('item', 'po', 'warehouse', 'item.unitType')
            ->where('po_id', $itemTransaction->po_id)
            ->first();
        $itemTransaction = GoodsTransaction::where('po_id', $itemTransaction->po_id)
            ->where('warehouse_id', $itemTransaction->warehouse_id)
            ->with('po', 'item', 'warehouse', 'item.unitType')
            ->first();

        $centralWarehouseStock = CentralWarehouseStock::where('po_id', $itemTransaction->po_id)->whereNull('qty')->count();

        return response()->json([
            'central_warehouse_item' => $centralWarehouseItem,
            'item_transaction' => $itemTransaction,
            'central_warehouse_stock' => $centralWarehouseStock
        ]);
    }

    public function IncomingItemData(): JsonResponse
    {
        return response()->json($this->incomingItemTransactionService->data());
    }

    public function outGoingItemData(): JsonResponse
    {
        $data = GoodsTransaction::with('po', 'item', 'item.unitType', 'warehouse')
            ->where('from_po', 0)
            ->where('type', 'out')->paginate(self::$perPage);
        return response()->json($data);
    }


    public function confirm(GoodsTransaction $itemTransaction): JsonResponse
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
