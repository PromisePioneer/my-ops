<?php

namespace App\Http\Controllers\Inventory;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\DraftStock;
use App\Models\ItemCollection;
use App\Support\Inventory\DraftStock\Service\DraftStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class DraftStockController extends Controller
{
    public function __construct()
    {
        $this->draftStockService = new DraftStockService();
    }

    public function index(): View
    {
        return view('pages.inventory.draft-stocks.index');
    }

    public function getQty(): JsonResponse
    {
        return response()->json($this->draftStockService->getQty());
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->draftStockService->data($request));
    }

    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->draftStockService->filter($request));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->draftStockService->search($request));
    }

    public function show(DraftStock $draftStock): JsonResponse
    {
        $draftStock->load('transaction', 'transaction.item', 'initialInventoryBalance.item');
        return response()->json($draftStock);
    }

    public function detail(ItemCollection $itemCollection): View
    {
        return view('pages.inventory.draft-stocks.detail', compact('itemCollection'));
    }

}
