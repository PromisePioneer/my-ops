<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\ItemCollection;
use App\Support\Inventory\DraftStock\Service\DraftStockDetailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class DraftStockDetailController extends Controller
{


    public function __construct()
    {
        $this->draftStockDetailService = new DraftStockDetailService();
    }

    public function draftStockByItemId(ItemCollection $itemCollection, Request $request): JsonResponse
    {
        $data = $this->draftStockDetailService->draftStockByItemId($itemCollection, $request);
        return response()->json($data);
    }
}
