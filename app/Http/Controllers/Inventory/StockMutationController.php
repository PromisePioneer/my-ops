<?php

namespace App\Http\Controllers\Inventory;

use AllowDynamicProperties;
use App\Enum\StockMutation\StockMutationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use App\Models\StockMutation;
use App\Support\Inventory\StockManagement\StockMutation\Service\StockMutationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
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
        $this->authorize('view', StockMutation::class);
        return view('pages.inventory.stock-mutations.index');
    }

    public function data(): JsonResponse
    {
        $this->authorize('view', StockMutation::class);
        return response()->json($this->stockMutationService->data());
    }


    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', StockMutation::class);
        return response()->json($this->stockMutationService->search($request));
    }


    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', StockMutation::class);
        return response()->json($this->stockMutationService->filter($request));
    }

    public function create(?ItemCollection $itemCollection): View
    {
        $this->authorize('create', StockMutation::class);
        return view('pages.inventory.stock-mutations.form', compact('itemCollection'));
    }


    /**
     * @throws Throwable
     */
    public function store(StockMutationRequest $request): JsonResponse
    {
        $this->authorize('create', StockMutation::class);
        $this->stockMutationService->store($request);
        return response()->json(['message' => 'stock  berhasil di mutasi']);
    }


    public function show(StockMutation $stockMutation): JsonResponse
    {
        $this->authorize('viewDetail', StockMutation::class);
        $stockMutation->load(
            'stockMutationItems',
            'stockMutationItems.stock.transaction.item',
            'sender',
            'receiver'
        );
        $stockMutation->date = formatDate($stockMutation->date);
        return response()->json($stockMutation);
    }


    public function receive(StockMutation $stockMutation): JsonResponse
    {
        $this->authorize('receive', StockMutation::class);
        $this->stockMutationService->receive($stockMutation);
        return response()->json(['message' => 'stock  berhasil di terima']);
    }


    public function destroy(Request $request, StockMutation $stockMutation): JsonResponse
    {
        $this->authorize('delete', StockMutation::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $stockMutation->whereIn('id', $explodeID)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }


    public function getSessions(): JsonResponse
    {
        $this->authorize('create', StockMutation::class);
        $stock = session()->get('stock_mutation_items') ?? [];
        return response()->json($stock);
    }


    public function sessionStore(Request $request): void
    {
        $this->authorize('create', StockMutation::class);
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
        $this->authorize('create', StockMutation::class);
        session()->forget('stock_mutation_items');
    }


    public function deleteSessions(Request $request): void
    {
        $this->authorize('create', StockMutation::class);
        Session::forget("stock_mutation_items.$request->index");
    }


    /**
     * @throws Throwable
     */
    public function cancelItemDelivery(StockMutation $stockMutation): void
    {
        $this->authorize('cancelDelivery', StockMutation::class);
        DB::transaction(function () use ($stockMutation) {
            $items = $stockMutation->load('stockMutationItems')->stockMutationItems;
            foreach ($items as $item) {
                if (!empty($item->code)) {
                    ItemCatalog::where('code', $item->code)->first()->update([
                        'status' => 'Tersedia'
                    ]);
                }

            }
            $stockMutation->update(['status' => StockMutationType::CANCELED->value]);
        });
    }

}
