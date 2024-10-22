<?php

namespace App\Http\Controllers\Inventory\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryCategoryRequest;
use App\Models\InventoryCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryCategoryController extends Controller
{

    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.operational-master-data.inventory-categories.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(InventoryCategory::paginate(self::$perPage));
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = InventoryCategory::orderBy('created_at');

        if (!empty($search)) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $category = $query->paginate(self::$perPage);
        return response()->json($category);
    }

    public function store(InventoryCategoryRequest $request): JsonResponse
    {
        InventoryCategory::create($request->validated());
        return response()->json(['message' => 'Data sukses disimpan.']);
    }

    public function edit(InventoryCategory $inventoryCategory): JsonResponse
    {
        return response()->json($inventoryCategory);
    }

    public function update(InventoryCategoryRequest $request, InventoryCategory $inventoryCategory): JsonResponse
    {
        return response()->json($inventoryCategory->update($request->validated()));
    }


    public function destroy(Request $request, InventoryCategory $inventoryCategory)
    {
        $this->authorize('delete', $request);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $inventoryCategory->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
