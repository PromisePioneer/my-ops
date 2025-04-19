<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\ItemCollection;
use App\Models\Stock;
use App\Models\Master\Common\Branch;
use App\Models\Warehouse;
use App\Support\GoodsTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

#[AllowDynamicProperties] class GoodsTransactionController extends Controller
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->goods = new ItemCollection();
        $this->branch = new Branch();
        $this->warehouse = new Warehouse();
        $this->goodsTransactionService = new GoodsTransactionService();
    }

    public function index(): View
    {
        return view('pages.inventory.goods.transactions.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->goodsTransactionService->data());
    }

    public function create(): View
    {
        return view('pages.inventory.goods.transactions.form');
    }


    public function getGoodsData(Request $request): JsonResponse
    {
        return response()->json($this->goods->getData($request));
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function getWarehouseData(Request $request): JsonResponse
    {
        return response()->json($this->warehouse->getData($request));
    }


    public function getStock(ItemCollection $goods): JsonResponse
    {
        $data = Stock::with('po', 'warehouse', 'branch', 'item')
            ->where(function ($query) use ($goods) {
                if (!empty(Auth::user()->branch_id)) {
                    $query->where('item_id', $goods->id)
                        ->where('branch_id', Auth::user()->branch_id);
                }
                if (empty(Auth::user()->branch_id)) {
                    $query->where('item_id', $goods->id)
                        ->whereNull('branch_id');
                }
            })->paginate(5);

        return response()->json($data);
    }

    public function selectedStock(Request $request, ItemCollection $goods): JsonResponse
    {
        $explodeID = explode(",", $request->selected_stock);
        $data = Stock::with('po', 'warehouse', 'branch', 'item')->whereIn('id', $explodeID)->paginate(5);
        return response()->json($data);
    }


    public function store(Request $request): JsonResponse
    {

        dd($request->all());
        $this->goodsTransactionService->store($request);
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


}
