<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\GoodsRequest;
use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\Master\Common\UnitType;
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
        $goods = Goods::with('category', 'unitType')->paginate(self::$perPage);
        return response()->json($goods);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $goods = Goods::search($search)->paginate(self::$perPage);
        return response()->json($goods);
    }

    public function store(GoodsRequest $request): JsonResponse
    {
        $unitType = UnitType::where('id', $request->unit_type_id)->first();
        $category = GoodsCategory::where('id', $request->category_id)->first();

        if (empty($category)) {
            $categoryId = GoodsCategory::create([
                'name' => $request->category_id
            ]);
        }

        if (empty($unitType)) {
            $unitTypeId = UnitType::create([
                'name' => $request->unit_type_id
            ]);
        }

        Goods::create([
            'name' => $request->name,
            'category_id' => $categoryId->id ?? $request->category_id,
            'unit_type_id' => $unitTypeId->id ?? $request->unit_type_id
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
        $goods->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
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


    public function getGoods(Request $request)
    {
        $search = $request->input('search');
        $goods = Goods::search($search)->query(function ($query) {
            $query->orderBy('name');
        })->get();

        return $goods->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
    }


    public function selectedGoods(Goods $goods): array
    {
        return [
            'id' => $goods->id,
            'name' => $goods->name,
        ];
    }


}
