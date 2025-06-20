<?php

namespace App\Http\Controllers\Inventory;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCollection;
use App\Models\StockMutation;
use App\Support\Inventory\StockManagement\StockMutation\Service\StockMutationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;
use function App\Helper\formatDate;

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


    public function search(Request $request): JsonResponse
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
        $stockMutation->load(
            'stockMutationItems',
            'stockMutationItems.stock.transaction.item',
            'stockMutationItems.stock.initialInventoryBalance.item',
            'sender',
            'receiver'
        );
        $stockMutation->date = formatDate($stockMutation->date);
        return response()->json($stockMutation);
    }


    public function destroy(Request $request, StockMutation $stockMutation): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $stockMutation->whereIn('id', $explodeID)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }





    public function getSessions(): JsonResponse
    {
        $stock = session()->get('stock_mutation_items') ?? [];
        return response()->json($stock);
    }


    public function sessionStore(Request $request): void
    {
        $stockMutationItem = [
            'code' => $request->get('code'),
            'qty' => $request->get('qty'),
            'stock_id' => $request->get('stock_id'),
            'item_id' => $request->get('item_id'),
            'item_name' => $request->get('item_name'),
        ];
        session()->push('stock_mutation_items', $stockMutationItem);
    }


    public function flushSessions(): void
    {
        session()->forget('stock_mutation_items');
    }


    public function deleteSessions(Request $request): void
    {
        Session::forget("stock_mutation_items.$request->index");

    }

}
