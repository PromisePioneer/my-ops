<?php

namespace App\Support\Master\Accounting\AccountCategories\Service;

use AllowDynamicProperties;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Support\Master\Accounting\AccountCategories\Repositories\AccountCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class AccountCategoryService
{

    private static int $perPage = 10;


    public function __construct()
    {
        $this->accountCategoryRepository = new AccountCategoryRepository();
        $this->accountCategory = new AccountCategory();
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->accountCategoryRepository->dataQuery()->paginate(self::$perPage);
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

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->accountCategoryRepository->dataQuery();


        if (!empty($search)) {
            $this->accountCategoryRepository->searchQuery($search, $query);
        }

        $data = $query->paginate(self::$perPage);

        return self::formattedData($data);
    }

    public function getAllAccountCategories(Request $request)
    {
        $search = $request->input('search');
        $categories = $this->accountCategoryRepository->getAllAccountCategories();


        if (!empty($search)) {
            $categories = $this->accountCategoryRepository->searchQuery($search, $categories);
        }

        return $categories->get()->map(function ($category) {
            return [
                'id' => $category?->id,
                'text' => $category?->name,
                'children' => $category->children->map(function ($child) {
                    return [
                        'id' => $child?->id,
                        'parent_id' => $child?->parent_id,
                        'text' => $child?->name,
                    ];
                })
            ];
        });
    }


    public function selectedAccountCategory(AccountCategory $accountCategory): array
    {
        return [
            'id' => $accountCategory?->id,
            'name' => $accountCategory?->name,
        ];
    }
}
