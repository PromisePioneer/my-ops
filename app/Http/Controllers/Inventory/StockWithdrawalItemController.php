<?php

namespace App\Http\Controllers\Inventory;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\StockWithdrawal;
use App\Support\Inventory\StockManagement\StockWithdrawal\Service\StockWithdrawalItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class StockWithdrawalItemController extends Controller
{
    public function __construct()
    {
        $this->stockWithdrawalItemService = new StockWithdrawalItemService();
    }


    public function index(): View
    {
        return view('pages.inventory.stock-withdrawal-items.index');
    }

    public function data(): JsonResponse
    {
        $carriedStocks = $this->stockWithdrawalItemService->data();
        return response()->json($carriedStocks);
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->stockWithdrawalItemService->search($request));
    }

    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->stockWithdrawalItemService->filter($request));
    }


    public function getCount(): JsonResponse
    {
        return response()->json($this->stockWithdrawalItemService->getCarriedStockCount());
    }


    public function getConsumedOrAppliedStock(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        return response()->json($this->stockWithdrawalItemService->getConsumedOrAppliedStock($stockWithdrawal));
    }

}
