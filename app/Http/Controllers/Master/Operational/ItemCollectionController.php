<?php

namespace App\Http\Controllers\Master\Operational;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Operational\Item\ItemCollectionRequest;
use App\Models\ItemCollection;
use App\Support\Master\Operational\ItemCollections\Service\ItemCollectionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

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

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', ItemCollection::class);
        return response()->json($this->itemCollectionService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('search', ItemCollection::class);
        return response()->json($this->itemCollectionService->search($request));
    }


    public function archivedSearch(Request $request): JsonResponse
    {
        return response()->json($this->itemCollectionService->archivedSearch($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', ItemCollection::class);
        return response()->json($this->itemCollectionService->filter($request));
    }


    public function archivedFilter(Request $request): JsonResponse
    {
        return response()->json($this->itemCollectionService->archivedFilter($request));
    }


    /**
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function store(ItemCollectionRequest $request): JsonResponse
    {
        $this->authorize('store', ItemCollection::class);
        $this->itemCollectionService->store($request);
        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(ItemCollection $itemCollection): JsonResponse
    {
        $itemCollection->load('unitType');
        $this->authorize('update', $itemCollection);
        return response()->json($itemCollection);
    }


    /**
     * @throws AuthorizationException
     */
    public function update(ItemCollection $itemCollection, ItemCollectionRequest $request): JsonResponse
    {
        $this->authorize('update', $itemCollection);
        $this->itemCollectionService->update($itemCollection, $request);
        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, ItemCollection $itemCollection): JsonResponse
    {
        $this->itemCollectionService->destroy($request, $itemCollection);
        $this->authorize('delete', $itemCollection);
        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function nonBuildingGroupDetails(): View
    {
        return view('pages.master.operational.items.non-building-group-desc');
    }


    public function getGoods(Request $request)
    {
        $search = $request->input('search');
        $goods = ItemCollection::with('unitType')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });

        return $goods->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'asset_account_id' => $item->asset_account_id,
                'must_have_code' => $item->must_have_code,
                'unit_type_name' => $item->unitType->name,
                'text' => $item->name
            ];
        });
    }


    public function getAssetItem(Request $request)
    {
        $search = $request->input('search');
        $goods = ItemCollection::with('unitType')
            ->where('type', 'ASET')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });

        return $goods->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'asset_account_id' => $item->asset_account_id,
                'must_have_code' => $item->must_have_code,
                'unit_type_name' => $item->unitType->name,
                'text' => $item->name,
            ];
        });
    }


    public function selectedItem(ItemCollection $item): array
    {
        $item->load('unitType');
        return [
            'id' => $item->id,
            'name' => $item->name,
            'unit_type_name' => $item->unitType->name
        ];
    }


    public function archived(): View
    {
        return view('pages.master.operational.items.archived');
    }

    public function archivedData(): JsonResponse
    {
        return response()->json($this->itemCollectionService->archivedData());
    }


    public function restore(Request $request, ItemCollection $itemCollection): JsonResponse
    {
        $this->itemCollectionService->restore($request, $itemCollection);
        return response()->json(['message' => 'Data berhasil di restore']);
    }


    public function getAssetData(): JsonResponse
    {
        return response()->json($this->itemCollectionService->assetData());
    }


}
