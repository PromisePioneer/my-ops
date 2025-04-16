<?php

namespace App\Support\Master\Operational\ItemCollections\Service;

use AllowDynamicProperties;
use App\Http\Requests\Master\Operational\Item\ItemCollectionRequest;
use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Master\Common\UnitType;
use App\Support\Master\Operational\ItemCollections\Repositories\ItemCollectionRepository;
use Illuminate\Http\JsonResponse;
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


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $itemCollections = ItemCollection::search($search)->query(function ($query) {
            $this->itemCollectionRepository->searchItemCollection($query);
        })->paginate(self::$perPage);

        return $this->formattedData($itemCollections);
    }


    public function formattedData(LengthAwarePaginator $itemCollections): LengthAwarePaginator
    {
        $data = $itemCollections->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item_collection_name,
                'unit_type_name' => $item->unitType?->name ?? $item->unit_type_name,
                'category_name' => $item->category?->name ?? $item->category_name,
                'asset_account_name' => $item->assetAccount?->name ?? $item->accounts,
                'material' => $item->material,
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

            $category = ItemCategory::find($request->category_id);
            $unitType = UnitType::find($request->unit_type_id);


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
                'material' => $request->material
            ]);
        });

    }


    public function update(ItemCollection $itemCollection, ItemCollectionRequest $request): void
    {
        $category = ItemCategory::where('id', $request->category_id)->first();

        $itemCollection->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_type_id' => $request->unit_type_id,
            'asset_account_id' => $category->name === 'ASET' ? $request->asset_account_id : null,
            'material' => $request->material
        ]);
    }


    public function destroy()
    {

    }
}
