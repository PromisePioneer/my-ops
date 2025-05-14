<?php

namespace App\Support\Master\Operational\ItemCollections\Repositories;

use App\Models\ItemCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ItemCollectionRepository
{
    public function getItemCollection(): Builder
    {
        return ItemCollection::with('category', 'unitType', 'assetAccount')
            ->select('id', 'name as item_collection_name', 'category_id', 'unit_type_id', 'asset_account_id', 'material', 'type', 'reorder_level')
            ->orderBy('item_collection_name');
    }


    public function searchItemCollection(QueryBuilder|EloquentBuilder $query): QueryBuilder|EloquentBuilder
    {
        return $query->join('item_categories', 'item_categories.id', 'item_collections.category_id')
            ->leftjoin('accounts', 'item_collections.asset_account_id', 'accounts.id')
            ->join('unit_types', 'unit_types.id', 'item_collections.unit_type_id')
            ->select('item_collections.*', 'item_collections.name as item_collection_name', 'item_categories.name', 'unit_types.name', 'accounts.name')
            ->orderBy('item_collections.name');
    }


    public function itemCollectionStock(): EloquentBuilder
    {
        return ItemCollection::with('stock', 'unitType');
    }
}
