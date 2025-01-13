<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\GoodsRequest;
use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\UnitType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class GoodsController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->goodsCategory = new GoodsCategory();
        $this->unitType = new UnitType();
    }

    public function index(): View
    {
        return view('pages.operational-master-data.goods.index');
    }

    public function data(): JsonResponse
    {
        $goods = Goods::with('category')->paginate(self::$perPage);
        return response()->json($goods);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $goods = Goods::when(!empty($search), function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);

        return response()->json($goods);
    }

    public function store(GoodsRequest $request): JsonResponse
    {
        $needSN = false;
        $snPerPo = false;

        if ($request->need_sn === "on") {
            $needSN = true;
        }

        if ($request->already_has_sn_on_item === "on") {
            $snPerPo = true;
        }

        Goods::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_type_id' => $request->unit_type_id,
            'need_sn' => $needSN,
            'already_has_sn_on_item' => $snPerPo,
        ]);
        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function edit(Goods $goods): JsonResponse
    {
        return response()->json($goods);
    }


    public function update(Goods $goods, GoodsRequest $request): JsonResponse
    {
        $needSN = false;
        $snPerPo = false;

        if ($request->need_sn === "on") {
            $needSN = true;
        }
        if ($request->already_has_sn_on_item === "on") {
            $snPerPo = true;
        }

        $goods->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_type_id' => $request->unit_type_id,
            'need_sn' => $needSN,
            'already_has_sn_on_item' => $snPerPo,
        ]);


        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function destroy(Request $request, Goods $goods): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goods->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function getGoodsCategory(Request $request): JsonResponse
    {
        return response()->json($this->goodsCategory->getData($request));
    }

    public function selectedGoodsCategory(Goods $goods): JsonResponse
    {
        return response()->json($this->goodsCategory->getSelectedData($goods->category_id));
    }


    public function getUnitTypes(Request $request): JsonResponse
    {
        return response()->json($this->unitType->getData($request));
    }

    public function selectedUnitType(Goods $goods): JsonResponse
    {
        return response()->json($this->unitType->getSelectedData($goods->unit_type_id));
    }



}
