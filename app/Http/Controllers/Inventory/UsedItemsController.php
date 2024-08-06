<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UsedItemsRequest;
use App\Models\Account;
use App\Models\Goods;
use App\Models\UsedItems;
use App\Service\UsedItemServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsedItemsController extends Controller
{
    public int $perPage = 10;

    protected UsedItemServices $usedItemsServices;

    public function __construct()
    {
        $this->usedItemsServices = new UsedItemServices;
        $this->usedItem = new UsedItems;
        $this->account = new Account;
    }

    public function getUsedItems(Goods $goods): JsonResponse
    {
        return response()->json($this->usedItem->getDataWithPaginationBasedOnGoods($goods->id, $this->perPage));
    }

    public function getAssetAccount(Request $request): JsonResponse
    {
        return response()->json($this->account->getAssetAccount($request));
    }

    public function usedItems(UsedItemsRequest $request, Goods $goods): JsonResponse
    {
        $this->usedItemsServices->store($request, $goods);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
