<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Branch;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use App\Models\Goods;
use App\Models\GoodsTransaction;
use App\Models\Warehouse;
use App\Service\CentralWareHouseStockService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class CentralWarehouseStockController extends Controller
{
    public function __construct()
    {
        $this->centralWarehouseStockService = new CentralWareHouseStockService();
        $this->branch = new Branch();
        $this->warehouse = new Warehouse();
    }

    public function data(GoodsTransaction $itemTransaction): JsonResponse
    {
        return response()->json($this->centralWarehouseStockService->data($itemTransaction));
    }


    public function search(Request $request, CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        return response()->json($this->centralWarehouseStockService->search($request, $centralWarehouseItem));
    }

    /**
     * @throws Throwable
     */
    public function generateSNIfExists(CentralWarehouseItem $centralWarehouseItem, Request $request): JsonResponse
    {
        $this->centralWarehouseStockService->generateSNIfExists($centralWarehouseItem, $request);

        if ($centralWarehouseItem->qty === 0) {
            return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
        }

        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    /**
     * @throws Throwable
     */
    public function generateSNIfNotExists(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        $this->centralWarehouseStockService->generateSNIfNotExists($centralWarehouseItem);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(CentralWarehouseStock $centralWarehouseStock): JsonResponse
    {
        return response()->json($centralWarehouseStock);
    }


    public function update(Request $request, CentralWarehouseStock $centralWarehouseStock): JsonResponse
    {
        $centralWarehouseStock->update([
            'sn' => $request->sn
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }


    public function confirm(Request $request, CentralWarehouseStock $centralWarehouseStock): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $centralWarehouseStock->whereIn('id', $explodeID)->update(['status' => 1]);

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    /**
     * @throws Throwable
     */
    public function destroy(Request $request, CentralWarehouseStock $centralWarehouseStock): JsonResponse
    {
        $this->centralWarehouseStockService->destroy($request, $centralWarehouseStock);
        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function getWarehouseData(Request $request): JsonResponse
    {
        return response()->json($this->warehouse->getData($request));
    }


    public function sendItem(Goods $item): View
    {
        return view('pages.inventory.list-of-items.central-warehouse.central-stock.send-item', compact('item'));
    }

    public function getStockWithSN(Goods $item, Request $request): JsonResponse
    {
        $warehouseId = $request->input('warehouse_id');
        $stock = CentralWarehouseStock::with('warehouse', 'po')->whereHas('warehouse', function ($query) use ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        })->where('item_id', $item->id)->where('status', 1)->whereNull('qty')->paginate(10);

        return response()->json($stock);
    }

    public function sendItemStore(Request $request, Goods $item)
    {
        $implodeID = implode(',', $request->get('selectedStock'));
        $explodeID = explode(',', $implodeID);
        $stock = CentralWarehouseStock::with('po')->where('item_id', $item->id)->whereIn('id', $explodeID)->get();
        foreach ($stock as $item) {
            DB::transaction(function () use ($stock, $item, $request) {
                $item->update([
                    'warehouse_id' => $request->warehouse_id,
                ]);

                $itemOut = GoodsTransaction::create([
                    'date' => Carbon::now(),
                    'po_id' => $item->po_id,
                    'item_id' => $item->item_id,
                    'warehouse_id' => $item->warehouse_id,
                    'qty' => $stock->count(),
                    'type' => 'out',
                ]);


                GoodsTransaction::create([
                    'date' => Carbon::now(),
                    'po_id' => $itemOut->po_id,
                    'item_id' => $itemOut->item_id,
                    'warehouse_id' => $itemOut->warehouse_id,
                    'qty' => $itemOut->qty,
                    'type' => 'in',
                ]);
            });
        }


    }




}
