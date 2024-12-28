<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    private static int $perPage = 10;

    public function index()
    {
        return view('pages.operational-master-data.items.index');
    }

    public function data(): JsonResponse
    {
        $item = Item::paginate(self::$perPage);
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
        Item::create($request->validated());
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
        $item->update($request->validated());
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
}
