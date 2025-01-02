<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use App\Service\CentralWareHouseStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

#[AllowDynamicProperties] class CentralWarehouseStockController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->centralWarehouseStockService = new CentralWareHouseStockService();
    }


    public function data(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        $centralWarehouseStock = CentralWarehouseStock::where('central_warehouse_item_id', $centralWarehouseItem->id)->paginate(self::$perPage);

        return response()->json($centralWarehouseStock);
    }

    public function generateSNAndCode(CentralWarehouseItem $centralWarehouseItem, Request $request): JsonResponse
    {
        DB::transaction(function () use ($centralWarehouseItem, $request) {
            if ($centralWarehouseItem->qty > 0) {
                CentralWarehouseStock::create([
                    'central_warehouse_item_id' => $centralWarehouseItem->id,
                    'sn' => $request->sn,
                    'code' => $this->centralWarehouseStockService->generateItemCode($centralWarehouseItem),
                ]);

                $centralWarehouseItem->decrement('qty');
            }
        });

        if ($centralWarehouseItem->qty === 0) {
            return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
        }

        return response()->json(['message' => 'Data berhasil disimpan']);
    }

}
