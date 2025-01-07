<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\BranchWarehouseItem;
use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
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


    public function getStockData()
    {
        $branchWarehouseItem = BranchWarehouseItem::
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->branchWarehouseItemService->search($request));
    }

    public function detail(BranchWarehouseItem $branchWarehouseItem): View
    {
        return view('pages.inventory.list-of-items.branch-warehouse-items.detail', compact('branchWarehouseItem'));
    }

    public function detailData(BranchWarehouseItem $branchWarehouseItem): JsonResponse
    {
        $items = BranchWarehouseItem::with('item', 'unitType', 'po', 'branch')
            ->where('id', $branchWarehouseItem->id)
            ->first();

        return response()->json($items);
    }
}
