<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\DraftStock;
use App\Support\Inventory\StockManagement\DraftStock\Service\DraftStockService;
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

    public function detail(DraftStock $draftStock): View
    {
        $draftStock->load('transaction', 'initialInventoryBalance');
        return view('pages.inventory.draft-stocks.detail', compact('draftStock'));
    }

}
