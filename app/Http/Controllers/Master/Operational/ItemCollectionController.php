<?php

namespace App\Http\Controllers\Master\Operational;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Operational\Item\ItemCollectionRequest;
use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Master\Common\UnitType;
use App\Support\Master\Operational\ItemCollections\Service\ItemCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class ItemCollectionController extends Controller
{

    public function __construct()
    {
        $this->itemCollectionService = new ItemCollectionService();
    }

    public function index(): View
    {
        return view('pages.master.operational.items.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->itemCollectionService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->itemCollectionService->search($request));
    }


    public function store(ItemCollectionRequest $request): JsonResponse
    {
        $this->itemCollectionService->store($request);
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
        $this->itemCollectionService->update($itemCollection, $request);
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
