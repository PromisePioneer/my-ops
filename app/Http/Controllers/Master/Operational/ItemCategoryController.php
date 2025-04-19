<?php

namespace App\Http\Controllers\Master\Operational;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Operational\ItemCategory\ItemCategoryRequest;
use App\Models\ItemCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemCategoryController extends Controller
{
    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.master.operational.item-categories.index');
    }


    public function data(): JsonResponse
    {
        $data = ItemCategory::query()->orderBy('name')->paginate(self::$perPage);
        return response()->json($data);
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $data = ItemCategory::search($search)->paginate(self::$perPage);
        return response()->json($data);
    }

    public function store(ItemCategoryRequest $request): JsonResponse
    {
        ItemCategory::create($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function edit(ItemCategory $itemCategory): JsonResponse
    {
        return response()->json($itemCategory);
    }


    public function update(ItemCategoryRequest $request, ItemCategory $itemCategory): JsonResponse
    {
        $itemCategory->update($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function destroy(Request $request, ItemCategory $itemCategory): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $itemCategory->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function getItemCategories(Request $request)
    {
        $search = $request->input('search');
        $goodsCategories = ItemCategory::search($search)
            ->query(fn($query) => $query->orderBy('name'))
            ->get();

        return $goodsCategories->map(function ($itemCategory) {
            return [
                'id' => $itemCategory->id,
                'text' => $itemCategory->name,
            ];
        });
    }


    public function selectedItemCategory(ItemCategory $itemCategory): array
    {
        return [
            'id' => $itemCategory->id,
            'name' => $itemCategory->name,
        ];
    }
}
