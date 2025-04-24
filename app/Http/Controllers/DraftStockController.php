<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Support\Inventory\Stock\DraftStockService;
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
        return view('pages.inventory.goods.stocks.draft-stocks.index');
    }

    public function getQty(): JsonResponse
    {
        return response()->json($this->draftStockService->getDraftStockQty());
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->draftStockService->data($request));
    }
}
