<?php

namespace App\Support\Master\Operational\ItemCollections\Service;

use AllowDynamicProperties;
use App\Http\Requests\Master\Operational\Item\ItemCollectionRequest;
use App\Models\ItemCollection;
use App\Models\Master\Common\UnitType;
use App\Support\Master\Accounting\Accounts\Repositories\AccountRepository;
use App\Support\Master\Common\UnitType\Repository\UnitTypeRepository;
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
        $this->itemCollection = new ItemCollection();
        $this->unitTypeRepository = new UnitTypeRepository();
        $this->unitType = new UnitType();
        $this->accountRepository = new AccountRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->itemCollectionRepository->getItemCollection()->paginate(self::$perPage);
        return self::formattedData($data);
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
        return self::formattedData($filter);
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
        $itemCollections = $this->itemCollection->search($search)->query(function ($query) {
            $this->itemCollectionRepository->searchItemCollection($query);
        })->paginate(self::$perPage);
        return $this->formattedData($itemCollections);
    }

    public function archivedSearch(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $itemCollections = $this->itemCollection->search($search)->query(function ($query) {
            $this->itemCollectionRepository->searchItemCollection($query);
        })->onlyTrashed()->paginate(self::$perPage);

        return $this->formattedData($itemCollections);
    }


    public function assetData(Request $request)
    {
        $search = $request->input('search');
        $items = ItemCollection::search($search)->query(function ($query) {
            $this->itemCollectionRepository->getAssetData();
        })->get();
        return $items->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
    }


    private static function formattedData(LengthAwarePaginator $itemCollections): LengthAwarePaginator
    {
        $data = $itemCollections->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item_collection_name,
                'unit_type_name' => $item->unitType?->name,
                'category_name' => $item->category?->name,
                'company_name' => $item->company?->name,
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
            $unitTypeId = $this->unitTypeStore($request);
            $assetAccount = $this->ifSpecificAssetAccount($request);

            ItemCollection::create([
                'name' => $request->input('name'),
                'is_vehicle' => $request->input('is_vehicle') === 'on',
                'category_id' => $request->input('category_id'),
                'unit_type_id' => $unitTypeId->id ?? $request->input('unit_type_id'),
                'code' => $request->input('code'),
                'asset_account_id' => $assetAccount,
                'must_have_code' => $request->input('must_have_code') === 'on',
                'is_code_listed' => $request->input('is_code_listed') === 'on',
                'tangible_assets_type' => $request->input('tangible_assets_type'),
                'reorder_level' => $request->input('reorder_level'),
                'type' => $request->input('type'),
                'building_type' => $request->input('building_type'),
                'non_building_group' => $request->input('non_building_group')
            ]);
        });
    }


    private function ifSpecificAssetAccount(ItemCollectionRequest $request)
    {

        if ($request->is_vehicle === 'on') {
            return $this->accountRepository->findByCode('123')->first()->id;
        }

        if ($request->tangible_assets_type === 'Tanah') {
            return $this->accountRepository->findByCode('121')->first()->id;
        }


        return $request->input('asset_account_id');
    }


    private function unitTypeStore(ItemCollectionRequest $request)
    {
        $unitType = $this->unitTypeRepository->findById($request->input('unit_type_id'));
        if (empty($unitType)) {
            return $this->unitType->query()->create([
                'name' => $request->input('unit_type_id'),
            ]);
        }

        return $request->input('unit_type_id');
    }


    public function update(ItemCollection $itemCollection, ItemCollectionRequest $request): bool
    {
        $unitTypeId = $this->unitTypeStore($request);
        $assetAccount = $this->ifSpecificAssetAccount($request);

        return $itemCollection->update([
            'name' => $request->input('name'),
            'is_vehicle' => $request->input('is_vehicle') === 'on',
            'category_id' => $request->input('category_id'),
            'unit_type_id' => $unitTypeId->id ?? $request->input('unit_type_id'),
            'code' => $request->input('code'),
            'asset_account_id' => $assetAccount,
            'must_have_code' => $request->input('must_have_code') === 'on',
            'is_code_listed' => $request->input('is_code_listed') === 'on',
            'tangible_assets_type' => $request->input('tangible_assets_type'),
            'reorder_level' => $request->input('reorder_level'),
            'type' => $request->input('type'),
            'building_type' => $request->input('building_type'),
            'non_building_group' => $request->input('non_building_group')
        ]);
    }


    public function destroy(Request $request, ItemCollection $itemCollection): void
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $itemCollection->query()->whereIn('id', array_filter($explodeID, 'is_numeric'))->delete();
    }


    public function restore(Request $request, ItemCollection $itemCollection): void
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $itemCollection->query()->whereIn('id', array_filter($explodeID, 'is_numeric'))->restore();
    }
}
