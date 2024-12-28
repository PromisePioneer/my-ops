<?php

namespace App\Http\Controllers\Inventory\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryCategoryRequest;
use App\Models\ItemCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemCategoryController extends Controller
{

    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.operational-master-data.item-categories.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(ItemCategory::paginate(self::$perPage));
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = ItemCategory::orderBy('created_at');

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $category = $query->paginate(self::$perPage);
        return response()->json($category);
    }

    public function store(InventoryCategoryRequest $request): JsonResponse
    {
        ItemCategory::create($request->validated());
        return response()->json(['message' => 'Data sukses disimpan.']);
    }

    public function edit(ItemCategory $itemCategory): JsonResponse
    {
        return response()->json($itemCategory);
    }

    public function update(InventoryCategoryRequest $request, ItemCategory $itemCategory): JsonResponse
    {
        return response()->json($itemCategory->update($request->validated()));
    }


    public function destroy(Request $request, ItemCategory $itemCategory)
    {
        $this->authorize('delete', $request);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $itemCategory->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
