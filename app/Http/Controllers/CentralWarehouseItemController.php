<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\CentralWarehouseItem;
use App\Service\CentralWarehouseItemService;
use App\Service\CentralWareHouseStockService;
use Illuminate\Http\JsonResponse;
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
        return response()->json($this->centralWarehouseStockService->data());
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





}
