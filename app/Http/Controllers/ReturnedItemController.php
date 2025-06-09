<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\StockWithdrawal;
use App\Support\Inventory\ReturnedItem\Service\ReturnedItemService;
use Illuminate\Http\JsonResponse;

#[AllowDynamicProperties] class ReturnedItemController extends Controller
{

    public function __construct()
    {
        $this->returnedItemService = new ReturnedItemService();
    }

    public function getReturnedItemByStockWithdrawalId(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        return response()->json($this->returnedItemService->getReturnedItemByStockWithdrawalId($stockWithdrawal));
    }
}
