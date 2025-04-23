<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Support\Inventory\Stock\DraftStockService;
use Illuminate\Http\JsonResponse;

#[AllowDynamicProperties] class DraftStockController extends Controller
{
    public function __construct()
    {
        $this->draftStockService = new DraftStockService();
    }

    public function getQty(): JsonResponse
    {
        return response()->json($this->draftStockService->getDraftStockQty());
    }
}
