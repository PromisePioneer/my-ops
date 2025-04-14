<?php

namespace App\Http\Controllers\Master\Operational;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Operational\Item\ItemCollectionRequest;
use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Master\Common\UnitType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class ItemCollectionController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->goodsCategory = new ItemCategory();
        $this->unitType = new UnitType();
    }

    public function index(): View
    {
        return view('pages.master.operational.items.index');
    }

    public function data(): JsonResponse
    {
        $goods = ItemCollection::with('category', 'unitType', 'assetAccount')->paginate(self::$perPage);
        return response()->json($goods);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $goods = ItemCollection::search($search)->paginate(self::$perPage);
        return response()->json($goods);
    }

    public function store(ItemCollectionRequest $request): JsonResponse
    {
        $unitType = UnitType::where('id', $request->unit_type_id)->first();
        $category = ItemCategory::where('id', $request->category_id)->first();


        if (empty($category)) {
            $categoryId = ItemCategory::create([
                'name' => $request->category_id
            ]);
        }

        if (empty($unitType)) {
            $unitTypeId = UnitType::create([
                'name' => $request->unit_type_id
            ]);
        }

        ItemCollection::create([
            'name' => $request->name,
            'category_id' => $categoryId->id ?? $request->category_id,
            'unit_type_id' => $unitTypeId->id ?? $request->unit_type_id,
            'asset_account_id' => $category->name === 'ASET' ? $request->asset_account_id : null,
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function edit(ItemCollection $itemCollection): JsonResponse
    {
        return response()->json($itemCollection);
    }


    public function update(ItemCollection $itemCollection, ItemCollectionRequest $request): JsonResponse
    {

        $category = ItemCategory::where('id', $request->category_id)->first();

        $itemCollection->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_type_id' => $request->unit_type_id,
            'asset_account_id' => $category->name === 'ASET' ? $request->asset_account_id : null,
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function destroy(Request $request, ItemCollection $goods): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goods->whereIn('id', $explodeID)->delete();


        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function getGoods(Request $request)
    {
        $search = $request->input('search');
        $goods = ItemCollection::search($search)->query(function ($query) {
            $query->orderBy('name');
        })->get();

        return $goods->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
    }


    public function selectedItem(ItemCollection $item): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
        ];
    }


}
