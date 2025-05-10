<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\StockWithdrawal;
use App\Support\Inventory\Stock\ItemCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

#[AllowDynamicProperties] class ItemCatalogController extends Controller
{

    public function __construct()
    {
        $this->itemCatalogService = new ItemCatalogService();
    }

    public function index()
    {

    }


    public function getCatalogByDraftStockId(Request $request, DraftStock $draftStock): JsonResponse
    {
        return response()->json($this->itemCatalogService->findByTransactionIdOrInitialBalanceInventoryId($draftStock, $request));
    }


    /**
     * @throws Throwable
     */
    public function storeByDraftStockId(ItemCatalogRequest $request, DraftStock $draftStock): JsonResponse
    {
        $this->itemCatalogService->storeByDraftStockId($request, $draftStock);
        return response()->json(['message' => 'data berhasil disimpan']);
    }

    public function edit(ItemCatalog $itemCatalog): JsonResponse
    {
        return response()->json($itemCatalog);
    }


    /**
     * @throws Throwable
     */
    public function destroy(ItemCatalog $itemCatalog): JsonResponse
    {
        $this->itemCatalogService->destroy($itemCatalog);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function getSelectedItemCatalogByStockWithdrawalId(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        return response()->json($this->itemCatalogService->getSelectedItemCatalogByStockWithdrawalId($stockWithdrawal));
    }

}
