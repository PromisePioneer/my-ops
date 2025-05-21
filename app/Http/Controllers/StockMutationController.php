<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCollection;
use App\Models\StockMutation;
use App\Support\Inventory\StockManagement\StockMutation\Service\StockMutationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class StockMutationController extends Controller
{
    public function __construct()
    {
        $this->stockMutationService = new StockMutationService();
    }


    public function index(): View
    {
        return view('pages.inventory.stock-mutations.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->stockMutationService->data());
    }


    public function search(Request $request)
    {
        return response()->json($this->stockMutationService->search($request));
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->stockMutationService->filter($request));
    }

    public function create(?ItemCollection $itemCollection): View
    {
        return view('pages.inventory.stock-mutations.form', compact('itemCollection'));
    }


    /**
     * @throws Throwable
     */
    public function store(StockMutationRequest $request): JsonResponse
    {
        $this->stockMutationService->store($request);
        return response()->json(['message' => 'stock  berhasil di mutasi']);
    }


    public function show(StockMutation $stockMutation): JsonResponse
    {
        $stockMutation->load('stockMutationItems', 'stockMutationItems.stock.item');
        return response()->json($stockMutation);
    }


    public function destroy(Request $request, StockMutation $stockMutation): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $stockMutation->whereIn('id', $explodeID)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }


}
