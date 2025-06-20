<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\ReturnedItemRequest;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\ReturnedItem\Service\ReturnedItemService;
use Illuminate\Http\JsonResponse;

#[AllowDynamicProperties] class ReturnedItemController extends Controller
{

    public function __construct()
    {
        $this->returnedItemService = new ReturnedItemService();
    }


    public function store(ReturnedItemRequest $request, StockWithdrawalItem $stockWithdrawalItem): JsonResponse
    {
        $this->returnedItemService->store($request, $stockWithdrawalItem);
        return response()->json(['message' => 'data berhasil di simpan']);
    }
}
