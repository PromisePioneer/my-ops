<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\GoodsRequest;
use App\Models\Branch;
use App\Models\Goods;
use App\Models\SubAccount;
use App\Models\UnitType;
use App\Service\GoodsServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GoodsController extends Controller
{
    public int $perPage = 10;

    private GoodsServices $goodsService;

    private Goods $goods;

    private UnitType $unitType;

    private SubAccount $subAccount;

    private Branch $branch;

    public function __construct()
    {
        $this->unitType = new UnitType;
        $this->goodsService = new GoodsServices;
        $this->goods = new Goods;
        $this->subAccount = new SubAccount;
        $this->branch = new Branch;
    }

    public function index(): View
    {
        return view('pages.inventory.goods.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->goods->getDataWithPaginationBasedOnUserBranch($request, $this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->goods->searchDataBasedOnUserBranch($request));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->goods->filterDataBasedOnBranch($branch->id, $this->perPage));
    }

    public function create(): View
    {
        return view('pages.inventory.goods.create');
    }

    public function getUnitTypesData(Request $request): JsonResponse
    {
        return response()->json($this->unitType->getData($request));
    }

    public function getRelatedAccounts(Request $request): array
    {
        return $this->subAccount->getAllPersediaanSubAccount($request);
    }

    public function store(GoodsRequest $request): JsonResponse
    {
        $file = $request->file('file')->store('goods/image', 'public');

        Goods::create([
            'account_id' => $request->account_id,
            'branch_id' => Auth::user()->branch_id,
            'unit_type_id' => $request->unit_type_id,
            'serial_number' => $request->serial_number,
            'qty' => $request->qty,
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'total_price' => $request->qty * $request->unit_price,
            'status' => $request->status,
            'file' => $file,
            'type' => $request->type,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'messaage' => 'data berhasil disimpan',
        ], 200);
    }

    public function edit(Goods $goods): View
    {
        return view('pages.inventory.goods.edit', compact('goods'));
    }

    public function getSelectedUnitType(Goods $goods): JsonResponse
    {
        return response()->json($this->unitType->getSelectedData($goods->unit_type_id));
    }

    public function getSelectedSubAccount(Request $request, Goods $goods): JsonResponse
    {
        return response()->json($this->subAccount->getSelectedSubAccount($request, $goods->account_id));
    }

    public function show(Goods $goods): JsonResponse
    {
        return response()->json($goods);
    }

    public function update(GoodsRequest $request, Goods $goods): JsonResponse
    {
        $file = $goods->file;

        if ($request->file('file')) {
            Storage::delete($file);
            $file = $request->file('file')->store('goods/image', 'public');
        }

        $goods->update([
            'account_id' => $request->account_id,
            'branch_id' => Auth::user()->branch_id,
            'unit_type_id' => $request->unit_type_id,
            'serial_number' => $request->serial_number,
            'qty' => $request->qty,
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'total_price' => $request->qty * $request->unit_price,
            'status' => $request->status,
            'file' => $file,
            'type' => $request->type,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    public function confirm(Goods $goods): JsonResponse
    {
        $this->goodsService->confirm($goods);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function useItemDetail(Goods $goods): View
    {
        return view('pages.inventory.goods.detail', compact('goods'));
    }

    public function destroy(Goods $goods): JsonResponse
    {
        $goods->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 204);
    }
}
