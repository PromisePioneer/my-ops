<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\CentralWarehouseItemDistributionRequest;
use App\Models\Branch;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use App\Models\Item;
use App\Models\ItemDistributionRecord;
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


    public function detail(CentralWarehouseItem $centralWarehouseItem): View
    {
        return view('pages.inventory.list-of-items.central-warehouse.item-distribution.detail', compact('centralWarehouseItem'));
    }


    public function detailData(CentralWarehouseItem $centralWarehouseItem): JsonResponse
    {
        $items = CentralWarehouseItem::with('item', 'po', 'warehouse', 'item.unitType')
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

    public function getStockData(): JsonResponse
    {
        return response()->json($this->centralWarehouseItemService->stockData());
    }

    public function getStockDataDetail(Item $item): JsonResponse
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


    public function distributeItemSave(CentralWarehouseItemDistributionRequest $request, CentralWarehouseItem $centralWarehouseItem)
    {

        $branch = Branch::where('id', $request->branch_id)->first();
        $warehouse = Warehouse::where('id', $request->warehouse_id)->first();

        ItemDistributionRecord::create([
            'po_number' => $centralWarehouseItem->po->po_number,
            'date' => $request->date,
            'item_name' => $centralWarehouseItem->item->name,
            'qty' => $request->qty,
            'from' => $centralWarehouseItem->warehouse?->name,
            'to' => $branch ?? $warehouse
        ]);
    }
}
