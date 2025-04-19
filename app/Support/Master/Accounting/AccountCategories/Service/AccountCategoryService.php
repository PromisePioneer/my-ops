<?php

namespace App\Support\Master\Accounting\AccountCategories\Service;

use App\Models\AccountCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountCategoryService
{

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = AccountCategory::with('children')->whereNull('parent_id')->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $accountCategory = $data->getCollection()->map(function ($accountCategory) {
            return [
                'account_category_id' => $accountCategory->id,
                'account_category_name' => $accountCategory->name,
                'sub_categories' => $accountCategory->children->map(static function ($subAccountCategory) {
                    return [
                        'sub_account_category_id' => $subAccountCategory->id,
                        'sub_account_category_name' => $subAccountCategory->name,
                    ];
                })
            ];
        });

        $data->setCollection($accountCategory);
        return $data;
    }
}
