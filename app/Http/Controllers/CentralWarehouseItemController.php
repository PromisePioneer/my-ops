<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Branch;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use App\Models\Goods;
use App\Models\ItemTransaction;
use App\Models\Warehouse;
use App\Service\CentralWarehouseItemService;
use App\Service\CentralWareHouseStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class CentralWarehouseItemController extends Controller
{
    public function __construct()
    {
        $this->centralWarehouseItemService = new CentralWarehouseItemService();
        $this->centralWarehouseStockService = new CentralWareHouseStockService();
        $this->branch = new Branch();
        $this->warehouse = new Warehouse();
    }

    public function index(): View
    {
        return view('pages.inventory.list-of-items.central-warehouse.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->centralWarehouseItemService->data());
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->centralWarehouseItemService->search($request));
    }


    public function getCentralWarehouseStockDetail(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        $centralWarehouseItemWithRelations = $centralWarehouseItem->with('item')->first();
        $centralWarehouseStock = CentralWarehouseStock::where('po_id', $centralWarehouseItem->po_id)
            ->where('status', 1)
            ->paginate(8);
        return response()->json([
            'central_warehouse_stock' => $centralWarehouseStock,
            'central_warehouse_item' => $centralWarehouseItemWithRelations
        ]);
    }

    public function getStockData(): JsonResponse
    {
        return response()->json($this->centralWarehouseItemService->stockData());
    }

    public function getStockDataDetail(Goods $item): JsonResponse
    {
        return response()->json($this->centralWarehouseItemService->getStockDataDetail($item));
    }


    public function distributeItem(CentralWarehouseItem $centralWarehouseItem): View
    {
        return view('pages.inventory.list-of-items.central-warehouse-items.item-distribution.distribute-item', compact('centralWarehouseItem'));
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function getWarehouseData(Request $request): JsonResponse
    {
        return response()->json($this->warehouse->getData($request));
    }

}
