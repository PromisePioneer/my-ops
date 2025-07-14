<?php

namespace App\Support\Master\Operational\ItemCollections\Repositories;

use AllowDynamicProperties;
use App\Models\ItemCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

#[AllowDynamicProperties] class ItemCollectionRepository
{

    public function __construct()
    {
        $this->itemCollection = new ItemCollection();
    }


    public function getItemCollection(): Builder
    {
        return $this->itemCollection->with(['category', 'unitType', 'assetAccount', 'company'])
            ->select('id', 'name as item_collection_name', 'category_id', 'unit_type_id', 'asset_account_id', 'type', 'reorder_level', 'tangible_assets_type', 'building_type', 'company_id')
            ->orderBy('item_collection_name');
    }


    public function searchItemCollection(QueryBuilder|EloquentBuilder $query): QueryBuilder|EloquentBuilder
    {
        return $query->join('item_categories', 'item_categories.id', 'item_collections.category_id')
            ->leftjoin('accounts', 'item_collections.asset_account_id', 'accounts.id')
            ->join('unit_types', 'unit_types.id', 'item_collections.unit_type_id')
            ->join('company', 'companies.id', 'item_collections.company_id')
            ->select('item_collections.*', 'item_collections.name as item_collection_name', 'item_categories.name', 'unit_types.name', 'accounts.name', 'companies.name as company_name')
            ->orderBy('item_collections.name');
    }


    public function itemCollectionStock(): EloquentBuilder
    {
        return $this->itemCollection->with(['unitType', 'category'])->whereNotNull('category_id');
    }

    public function getArchivedData()
    {
        return $this->itemCollection->onlyTrashed()->with(['category', 'unitType', 'assetAccount'])
            ->select('id', 'name as item_collection_name', 'category_id', 'unit_type_id', 'asset_account_id', 'type', 'reorder_level', 'tangible_assets_type', 'building_type')
            ->orderBy('name');
    }


    public function getAssetData()
    {
        return $this->itemCollection->with(['category', 'unitType', 'assetAccount'])->where('type', 'ASET');
    }


    public function getMustReorderItem(): EloquentBuilder
    {

        return $this->itemCollection->query();
    }


    public function findById(int $id): array|ItemCollection
    {
        return $this->itemCollection->find($id);
    }
}
