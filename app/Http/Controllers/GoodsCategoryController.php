<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\UnitTypeRequest;
use App\Models\GoodsCategory;
use App\Models\Master\Common\UnitType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoodsCategoryController extends Controller
{
    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.master.common.unit-types.index');
    }


    public function data(): JsonResponse
    {
        $data = UnitType::query()->orderBy('name')->paginate(self::$perPage);
        return response()->json($data);
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $data = UnitType::search($search)->paginate(self::$perPage);
        return response()->json($data);
    }

    public function store(UnitTypeRequest $request): JsonResponse
    {
        UnitType::create($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function edit(UnitType $unitType): JsonResponse
    {
        return response()->json($unitType);
    }


    public function update(UnitTypeRequest $request, UnitType $unitType): JsonResponse
    {
        $unitType->update($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function destroy(Request $request, UnitType $unitType): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $unitType->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function getGoodsCategories(Request $request)
    {
        $search = $request->input('search');
        $goodsCategories = GoodsCategory::search($search)
            ->query(fn($query) => $query->orderBy('name'))
            ->get();

        return $goodsCategories->map(function ($unitType) {
            return [
                'id' => $unitType->id,
                'text' => $unitType->name,
            ];
        });
    }


    public function getSelectedGoodsCategory(GoodsCategory $goodsCategory): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
