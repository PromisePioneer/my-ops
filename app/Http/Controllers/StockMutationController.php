<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StockMutationController extends Controller
{
    public function __construct()
    {

    }

    public function create(ItemCollection $itemCollection): View
    {
        return view('pages.inventory.stock-mutation.form', compact('itemCollection'));
    }


    public function store(StockMutationRequest $request): JsonResponse
    {
        $implodeID = implode(',', $request->item_collection_id);
        $explodeID = explode(',', $implodeID);
        $itemCatalog = ItemCatalog::whereIn('id', $explodeID)->get();

        return response()->json([
            'message' => 'stock  berhasil di mutasi',
            'data' => $itemCatalog
        ]);
    }


}
