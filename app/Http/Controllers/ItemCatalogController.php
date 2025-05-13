<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Support\Inventory\StockManagement\ItemCatalogService;
use Illuminate\Http\JsonResponse;
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


    public function findByDraftStock(DraftStock $draftStock): JsonResponse
    {
        return response()->json($this->itemCatalogService->findByDraftStock($draftStock));
    }


    /**
     * @throws Throwable
     */
    public function store(ItemCatalogRequest $request, DraftStock $draftStock): JsonResponse
    {
        $this->itemCatalogService->store($request, $draftStock);
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


    public function generateAutomaticItemCode(DraftStock $draftStock): JsonResponse
    {
        return response()->json($this->itemCatalogService->generateAutomaticItemCode($draftStock));
    }


}
