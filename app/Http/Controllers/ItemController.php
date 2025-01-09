<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\UnitType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class ItemController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCategory = new ItemCategory();
        $this->unitType = new UnitType();
    }

    public function index(): View
    {
        return view('pages.operational-master-data.items.index');
    }

    public function data(): JsonResponse
    {
        $item = Item::with('category')->paginate(self::$perPage);
        return response()->json($item);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $item = Item::when(!empty($search), function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);

        return response()->json($item);
    }

    public function store(ItemRequest $request): JsonResponse
    {
        $needSN = false;
        $snPerPo = false;

        if ($request->need_sn === "on") {
            $needSN = true;
        }

        if ($request->already_has_sn_on_item === "on") {
            $snPerPo = true;
        }

        Item::create([
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


    public function edit(Item $item): JsonResponse
    {
        return response()->json($item);
    }


    public function update(Item $item, ItemRequest $request): JsonResponse
    {
        $needSN = false;
        $snPerPo = false;

        if ($request->need_sn === "on") {
            $needSN = true;
        }
        if ($request->already_has_sn_on_item === "on") {
            $snPerPo = true;
        }

        $item->update([
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


    public function destroy(Request $request,Item $item): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $item->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function getItemCategories(Request $request): JsonResponse
    {
        return response()->json($this->itemCategory->getData($request));
    }

    public function selectedItemCategories(Item $item): JsonResponse
    {
        return response()->json($this->itemCategory->getSelectedData($item->category_id));
    }


    public function getUnitTypes(Request $request): JsonResponse
    {
        return response()->json($this->unitType->getData($request));
    }

    public function selectedUnitType(Item $item): JsonResponse
    {
        return response()->json($this->unitType->getSelectedData($item->unit_type_id));
    }



}
