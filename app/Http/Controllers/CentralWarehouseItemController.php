<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
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
    }

    public function index(): View
    {
        return view('pages.inventory.list-of-items.central-warehouse-items.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->centralWarehouseItemService->data());
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->centralWarehouseItemService->search($request));
    }


    public function detail(CentralWarehouseItem $centralWarehouseItem): View
    {
        return view('pages.inventory.list-of-items.central-warehouse-items.detail', compact('centralWarehouseItem'));
    }


    public function detailData(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        $items = CentralWarehouseItem::with('item', 'unitType', 'po', 'warehouse')
            ->where('id', $centralWarehouseItem->id)
            ->first();

        return response()->json($items);
    }


    public function getCentralWarehouseStockDetail(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {

        $centralWarehouseItemWithRelations = $centralWarehouseItem->with('item')->first();
        $centralWarehouseStock = CentralWarehouseStock::where('central_warehouse_item_id', $centralWarehouseItem->id)->where('status', 1)->paginate(8);
        return response()->json([
            'central_warehouse_stock' => $centralWarehouseStock,
            'central_warehouse_item' => $centralWarehouseItemWithRelations
        ]);
    }


    public function distributeStock(): JsonResponse
    {
        return response()->json();
    }
}
