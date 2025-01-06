<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\BranchWarehouseItem;
use App\Models\BranchWarehouseStock;
use App\Service\BranchWarehouseStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

#[AllowDynamicProperties] class BranchWarehouseStockController extends Controller
{
    public function __construct()
    {
        $this->branchWarehouseStockService = new BranchWarehouseStockService();
    }

    public function data(BranchWarehouseItem $branchWarehouseItem): JsonResponse
    {
        return response()->json($this->branchWarehouseStockService->data($branchWarehouseItem));
    }

    public function generateSNIfExists(BranchWarehouseItem $branchWarehouseItem, Request $request): JsonResponse
    {
        $this->branchWarehouseStockService->generateSNIfExists($branchWarehouseItem, $request);

        if ($branchWarehouseItem->qty === 0) {
            return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
        }

        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function generateSNIfNotExists(BranchWarehouseItem $branchWarehouseItem): JsonResponse
    {
        $this->branchWarehouseStockService->generateSNIfNotExists($branchWarehouseItem);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function confirm(BranchWarehouseStock $branchWarehouseStock, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $branchWarehouseStock->whereIn('id', $explodeID)->update(['status' => 1]);

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    /**
     * @throws Throwable
     */
    public function destroy(BranchWarehouseStock $branchWarehouseStock, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);

        DB::transaction(function () use ($branchWarehouseStock, $explodeID) {
            $branchWarehouseStocks = BranchWarehouseStock::find($explodeID[0])->branch_warehouse_item_id;
            BranchWarehouseItem::where('id', $branchWarehouseStocks)->increment('qty', count($explodeID));
            $branchWarehouseStock->whereIn('id', $explodeID)->delete();
        });

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
