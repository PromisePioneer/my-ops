<?php

namespace App\Support\Master\Operational\ItemCollections\Service;

use AllowDynamicProperties;
use App\Http\Requests\Master\Operational\Item\ItemCollectionRequest;
use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Master\Common\UnitType;
use App\Support\Master\Operational\ItemCollections\Repositories\ItemCollectionRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

#[AllowDynamicProperties] class ItemCollectionService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCollectionRepository = new ItemCollectionRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->itemCollectionRepository->getItemCollection()->paginate(self::$perPage);
        return $this->formattedData($data);
    }


    public function archivedData(): LengthAwarePaginator
    {
        $data = $this->itemCollectionRepository->getArchivedData()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->itemCollectionRepository->getItemCollection();
        $filter = ItemCollectionFilter::apply($query, $request)->paginate(self::$perPage);
        return $this->formattedData($filter);
    }

    public function archivedFilter(Request $request): LengthAwarePaginator
    {
        $query = $this->itemCollectionRepository->getArchivedData();
        $filter = ItemCollectionFilter::apply($query, $request)->paginate(self::$perPage);
        return $this->formattedData($filter);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $itemCollections = ItemCollection::search($search)->query(function ($query) {
            $this->itemCollectionRepository->searchItemCollection($query);
        })->paginate(self::$perPage);
        return $this->formattedData($itemCollections);
    }

    public function archivedSearch(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $itemCollections = ItemCollection::search($search)->query(function ($query) {
            $this->itemCollectionRepository->searchItemCollection($query);
        })->onlyTrashed()->paginate(self::$perPage);

        return $this->formattedData($itemCollections);
    }


    public function assetData()
    {
        $items = $this->itemCollectionRepository->getAssetData()->get();
        return $items->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
    }


    public function formattedData(LengthAwarePaginator $itemCollections): LengthAwarePaginator
    {
        $data = $itemCollections->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item_collection_name,
                'unit_type_name' => $item->unitType?->name,
                'category_name' => $item->category?->name,
                'category_id' => $item->category_id,
                'asset_account_name' => "{$item->assetAccount?->code} {$item->assetAccount?->name}",
                'tangible_asset' => $item->tangible_assets_type,
                'type' => $item->type,
                'reorder_level' => $item->reorder_level
            ];
        });

        $itemCollections->setCollection($data);
        return $itemCollections;
    }


    /**
     * @throws Throwable
     */
    public function store(ItemCollectionRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $categoryId = null;
            $category = ItemCategory::find($request->category_id);
            $unitType = UnitType::find($request->unit_type_id);
            if ($request->category_id) {
                $categoryId = ItemCategory::create([
                    'name' => $request->category_id
                ]);
            }

            if (empty($unitType)) {
                $unitTypeId = UnitType::create([
                    'name' => $request->unit_type_id
                ]);
            }


            $reorderLevel = null;
            $buildingType = null;
            $tangibleAssetsType = null;
            if ($request->type === 'ASET') {
                $tangibleAssetsType = $request->tangible_assets_type;
            }
            if ($request->tangible_assets_type === 'Bukan Bangunan' || $request->type === 'JUAL') {
                $reorderLevel = $request->reorder_level;
            }
            if ($request->tangible_assets_type === 'Bangunan') {
                $buildingType = $request->building_type;
            }

            if ($request->tangible_assets_type === 'Bukan Bangunan'
                || $request->type === 'ASET'
                || $request->type === 'JUAL'
            ) {
                $categoryId = $request->category_id;
            }

            ItemCollection::create([
                'name' => $request->name,
                'category_id' => $categoryId,
                'unit_type_id' => $unitTypeId->id ?? $request->unit_type_id,
                'code' => $request->code,
                'asset_account_id' => $request->type === 'ASET' ? $request->asset_account_id : null,
                'material' => $request->material,
                'must_have_code' => $request->must_have_code === 'on',
                'is_code_listed' => $request->is_code_listed === 'on',
                'tangible_assets_type' => $tangibleAssetsType,
                'reorder_level' => $reorderLevel,
                'type' => $request->type,
                'building_type' => $buildingType,
            ]);
        });
    }


    public function update(ItemCollection $itemCollection, ItemCollectionRequest $request): void
    {

        $reorderLevel = null;
        $buildingType = null;
        $categoryId = null;
        $tangibleAssetsType = null;
        if ($request->type === 'ASET') {
            $tangibleAssetsType = $request->tangible_assets_type;
        }
        if ($request->tangible_assets_type === 'Bukan Bangunan' || $request->type === 'JUAL') {
            $reorderLevel = $request->reorder_level;
        }
        if ($request->tangible_assets_type === 'Bangunan') {
            $buildingType = $request->building_type;
        }

        if ($request->tangible_assets_type === 'Bukan Bangunan'
            || $request->type === 'ASET'
            || $request->type === 'JUAL'
        ) {
            $categoryId = $request->category_id;
        }

        $itemCollection->update([
            'name' => $request->name,
            'category_id' => $categoryId,
            'unit_type_id' => $request->unit_type_id,
            'code' => $request->code,
            'type' => $request->type,
            'asset_account_id' => $request->type === 'ASET' ? $request->asset_account_id : null,
            'material' => $request->material,
            'must_have_code' => $request->must_have_code === 'on',
            'is_code_listed' => $request->is_code_listed === 'on',
            'tangible_assets_type' => $tangibleAssetsType,
            'reorder_level' => $reorderLevel,
            'building_type' => $buildingType,
        ]);
    }


    public function destroy(Request $request, ItemCollection $itemCollection): void
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $itemCollection->whereIn('id', array_filter($explodeID, 'is_numeric'))->delete();
    }


    public function restore(Request $request, ItemCollection $itemCollection): void
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $itemCollection->whereIn('id', array_filter($explodeID, 'is_numeric'))->restore();
    }


}
