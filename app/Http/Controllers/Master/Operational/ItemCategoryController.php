<?php

namespace App\Http\Controllers\Master\Operational;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Operational\ItemCategory\ItemCategoryRequest;
use App\Models\ItemCategory;
use App\Support\Master\Operational\ItemCategory\Service\ItemCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class ItemCategoryController extends Controller
{
    public function __construct()
    {
        $this->itemCategoryService = new ItemCategoryService();
    }

    public function index(): View
    {
        $this->authorize('view', ItemCategory::class);
        return view('pages.master.operational.item-categories.index');
    }


    public function data(): JsonResponse
    {
        $this->authorize('view', ItemCategory::class);
        return response()->json($this->itemCategoryService->data());
    }


    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', ItemCategory::class);
        return response()->json($this->itemCategoryService->search($request));
    }

    public function store(ItemCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', ItemCategory::class);
        ItemCategory::create($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function edit(ItemCategory $itemCategory): JsonResponse
    {
        $this->authorize('update', ItemCategory::class);
        return response()->json($itemCategory);
    }


    public function update(ItemCategoryRequest $request, ItemCategory $itemCategory): JsonResponse
    {
        $this->authorize('update', $itemCategory);
        $itemCategory->update($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function destroy(Request $request, ItemCategory $itemCategory): JsonResponse
    {
        $this->authorize('delete', $itemCategory);
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
