<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use App\Service\CentralWareHouseStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

#[AllowDynamicProperties] class CentralWarehouseStockController extends Controller
{

    public function __construct()
    {
        $this->centralWarehouseStockService = new CentralWareHouseStockService();
    }

    public function data(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        return response()->json($this->centralWarehouseStockService->data($centralWarehouseItem));
    }


    public function search(Request $request, CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        return response()->json($this->centralWarehouseStockService->search($request, $centralWarehouseItem));
    }

    /**
     * @throws Throwable
     */
    public function generateSNAndCode(CentralWarehouseItem $centralWarehouseItem, Request $request): JsonResponse
    {
        $this->centralWarehouseStockService->generateSNAndCode($centralWarehouseItem, $request);

        if ($centralWarehouseItem->qty === 0) {
            return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
        }

        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    /**
     * @throws Throwable
     */
    public function generateCentralWarehouseItemCodeIfSNDoesntExists(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        $this->centralWarehouseStockService->generateCentralWarehouseItemCodeIfSNDoesntExists($centralWarehouseItem);
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


    public function destroy(Request $request, CentralWarehouseStock $centralWarehouseStock): JsonResponse
    {
        $this->centralWarehouseStockService->destroy($request, $centralWarehouseStock);
        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }

}
