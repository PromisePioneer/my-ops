<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use App\Models\StockMutationHasItemCatalog;
use App\Models\StockMutationHistory;
use App\Support\Inventory\StockManagement\StockMutation\Service\StockMutationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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


    public function filter(Request $request)
    {
        return response()->json($this->stockMutationService->filter($request));
    }

    public function create(?ItemCollection $itemCollection): View
    {
        return view('pages.inventory.stock-mutations.form', compact('itemCollection'));
    }


    public function store(StockMutationRequest $request, ItemCollection $itemCollection): JsonResponse
    {
        $implodeID = implode(',', $request->item_collection_id);
        $explodeID = explode(',', $implodeID);
        $itemCatalog = ItemCatalog::with('stock.branch.parent')
            ->whereIn('id', $explodeID)
            ->get();

        $stockMutation = StockMutationHistory::create([
            'date' => Carbon::now()->format('Y-m-d'),
            'old_branch_id' => $request->from_branch,
            'new_branch_id' => $request->to_branch,
            'item_id' => $itemCollection->id,
            'stocker_id' => $request->user()->id
        ]);

        if ($itemCollection->must_have_code) {
            foreach ($itemCatalog as $item) {
                StockMutationHasItemCatalog::create([
                    'item_catalog_id' => $item->id,
                    'mutation_histories_id' => $stockMutation->id,
                ]);
            }
        }


        return response()->json(['message' => 'stock  berhasil di mutasi']);
    }


}
