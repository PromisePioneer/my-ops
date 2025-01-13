<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\BranchWarehouseItem;
use App\Models\Goods;
use App\Service\BranchWarehouseItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchWarehouseItemController extends Controller
{
    public function __construct()
    {
        $this->branchWarehouseItemService = new BranchWarehouseItemService();
    }

    public function index(): View
    {
        return view('pages.inventory.list-of-items.branch-warehouse-items.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->branchWarehouseItemService->data());
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->branchWarehouseItemService->search($request));
    }

    public function searchStockData(Request $request): JsonResponse
    {
        return response()->json($this->branchWarehouseItemService->searchStockData($request));
    }

    public function detail(BranchWarehouseItem $branchWarehouseItem): View
    {
        return view('pages.inventory.list-of-items.branch-warehouse-items.detail', compact('branchWarehouseItem'));
    }

    public function detailData(BranchWarehouseItem $branchWarehouseItem): JsonResponse
    {
        $items = BranchWarehouseItem::with('item', 'po', 'branch', 'item.unitType')
            ->where('id', $branchWarehouseItem->id)
            ->first();

        return response()->json($items);
    }

    public function getStockData(): JsonResponse
    {
        return response()->json($this->branchWarehouseItemService->stockData());
    }


    public function getStockDataDetail(Goods $item): JsonResponse
    {
        return response()->json($this->branchWarehouseItemService->getStockDataDetail($item));
    }

}
