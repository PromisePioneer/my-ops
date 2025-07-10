<?php

namespace App\Support\Master\Accounting\AccountCategories\Repositories;

use AllowDynamicProperties;
use App\Models\AccountCategory;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class AccountCategoryRepository
{


    public function __construct()
    {
        $this->accountCategory = new AccountCategory();
    }

    public function dataQuery(): Builder
    {
        return $this->accountCategory->with(['children', 'parent'])
            ->whereNull('parent_id')
            ->orderBy('name');
    }


    public function searchQuery(string $search, Builder $query): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->whereHas('children', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('name', 'like', '%' . $search . '%');
        });
    }
}
