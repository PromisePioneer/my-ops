<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class ItemController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCategory = new ItemCategory();
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
        $item = Item::when(!$search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);

        return response()->json($item);
    }

    public function store(ItemRequest $request): JsonResponse
    {
        $needSN = false;

        if ($request->need_sn === "on") {
            $needSN = true;
        }


        Item::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'need_sn' => $needSN
        ]);
        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function edit(Item $item): JsonResponse
    {
        return response()->json($item);
    }


    public function update(Item $item, ItemRequest $request)
    {
        $needSN = false;

        if ($request->need_sn === "on") {
            $needSN = true;
        }

        $item->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'need_sn' => $needSN
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
}
