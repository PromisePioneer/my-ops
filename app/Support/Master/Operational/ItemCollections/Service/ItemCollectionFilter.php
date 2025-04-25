<?php

namespace App\Support\Master\Operational\ItemCollections\Service;

use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class ItemCollectionFilter
{
    public static function apply(Builder|EloquentBuilder $query, $request): Builder|EloquentBuilder
    {
        if ($request->filled('category_id')) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('id', $request->input('category_id'));
            });
        }

        return $query;
    }
}
