<?php

namespace App\Support\Master\Operational\ItemCategory\Service;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemCategoryService
{

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $itemCategories = ItemCategory::with('itemCollections')->orderBy('name')->paginate(self::$perPage);
        return self::formattedData($itemCategories);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $itemCategories = ItemCategory::search($search)->paginate(self::$perPage);
        return self::formattedData($itemCategories);
    }


    public static function formattedData(LengthAwarePaginator $itemCategories): LengthAwarePaginator
    {
        $data = $itemCategories->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'name' => $query->name,
                'asset_items' => $query->itemCollections->where('type', 'ASET')->map(function ($query) {
                    return [
                        'id' => $query->id,
                        'name' => $query->name,
                    ];
                }),
                'sell_items' => $query->itemCollections->where('type', 'JUAL')->map(function ($query) {
                    return [
                        'id' => $query->id,
                        'name' => $query->name,
                    ];
                }),
            ];
        });


        $itemCategories->setCollection($data);
        return $itemCategories;
    }
}
