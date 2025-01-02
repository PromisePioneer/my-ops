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

            if ($centralWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }


            $lastStock = CentralWarehouseStock::where('central_warehouse_item_id', $centralWarehouseItem->id)
                ->lockForUpdate()
                ->latest()
                ->first();

            $array = explode('.', $lastStock?->code);
            $startNumber = $lastStock ? (int)end($array) : 0;

            CentralWarehouseStock::create([
                'central_warehouse_item_id' => $centralWarehouseItem->id,
                'sn' => $request->sn,
                'code' => $this->centralWarehouseStockService->generateCodeWithNumber($centralWarehouseItem, $startNumber),
            ]);

            $centralWarehouseItem->decrement('qty');
        });

        if ($centralWarehouseItem->qty === 0) {
            return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
        }

        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function generateCentralWarehouseItemCodeIfSNDoesntExists(CentralWarehouseItem $centralWarehouseItem, Request $request): JsonResponse
    {
        return DB::transaction(function () use ($centralWarehouseItem) {
            if ($centralWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }

            $lastStock = CentralWarehouseStock::where('central_warehouse_item_id', $centralWarehouseItem->id)
                ->lockForUpdate()
                ->latest()
                ->first();

            $array = explode('.', $lastStock?->code);
            $startNumber = $lastStock ? (int)end($array) : 0;

//            dd($startNumber);

            $stocks = [];
            for ($i = 0; $i < $centralWarehouseItem->qty; $i++) {
                $startNumber++;
                $stocks[] = [
                    'central_warehouse_item_id' => $centralWarehouseItem->id,
                    'code' => $this->centralWarehouseStockService->generateCodeWithNumber($centralWarehouseItem, $i),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            CentralWarehouseStock::insert($stocks);
//            $centralWarehouseItem->update(['qty' => 0]);

            return response()->json(['message' => 'Data berhasil disimpan']);
        });
    }

}
