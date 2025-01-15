<?php

namespace App\Http\Controllers\Inventory\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryCategoryRequest;
use App\Models\GoodsCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoodsCategoryController extends Controller
{

    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.operational-master-data.category-of-goods.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(GoodsCategory::paginate(self::$perPage));
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = GoodsCategory::orderBy('created_at');

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $category = $query->paginate(self::$perPage);
        return response()->json($category);
    }

    public function store(InventoryCategoryRequest $request): JsonResponse
    {
        GoodsCategory::create($request->validated());
        return response()->json(['message' => 'Data sukses disimpan.']);
    }

    public function edit(GoodsCategory $goodsCategory): JsonResponse
    {
        return response()->json($goodsCategory);
    }

    public function update(InventoryCategoryRequest $request, GoodsCategory $goodsCategory): JsonResponse
    {
        return response()->json($goodsCategory->update($request->validated()));
    }


    public function destroy(Request $request, GoodsCategory $goodsCategory)
    {
        $this->authorize('delete', $request);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsCategory->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
