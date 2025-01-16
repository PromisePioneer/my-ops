<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Branch;
use App\Models\Goods;
use App\Models\GoodsStock;
use App\Models\GoodsTransaction;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

#[AllowDynamicProperties] class GoodsTransactionController extends Controller
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->goods = new Goods();
        $this->branch = new Branch();
        $this->warehouse = new Warehouse();
    }

    public function index(): View
    {
        return view('pages.inventory.goods.transactions.index');
    }

    public function data(): JsonResponse
    {
        $data = GoodsTransaction::with('po', 'item', 'warehouse', 'branch', 'po.branch', 'po.warehouse', 'po.sendBy', 'po.receivedBy')
            ->orderBy('id', 'desc')->paginate(self::$perPage);
        return response()->json($data);
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

    public function getStock(Goods $goods): JsonResponse
    {
        $data = GoodsStock::with('po', 'warehouse', 'branch')
            ->where(function ($query) use ($goods) {
                if (!empty(Auth::user()->branch_id)) {
                    $query->where('item_id', $goods->id)->where('branch_id', Auth::user()->branch_id);
                }
                if (empty(Auth::user()->branch_id)) {
                    $query->where('item_id', $goods->id)->whereNull('branch_id');
                }
            })->paginate(5);


        return response()->json($data);
    }

    public function selectedStock(Request $request, Goods $goods): JsonResponse
    {
        $explodeID = explode(",", $request->selected_stock);
        $data = GoodsStock::with('po','warehouse', 'branch')->whereIn('id', $explodeID)->paginate(5);
        return response()->json($data);
    }


}
