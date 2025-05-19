<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use AllowDynamicProperties;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class StockMutationService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->stockMutationRepository = new StockMutationRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $stockMutations = $this->stockMutationRepository->getData()->paginate(self::$perPage);
        return self::formattedData($stockMutations);
    }

    public function search(Request $request)
    {
    }

    public function filter(Request $request)
    {
    }

    public function formattedData(LengthAwarePaginator $stockMutations): LengthAwarePaginator
    {
        $data = $stockMutations->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'date' => formatDate($query->date),
                'old_branch_name' => $query->oldBranch->name,
                'item_name' => $query->item->name,
                'new_branch_name' => $query->newBranch->parent->name,
                'stocker_name' => $query->stocker->name,
            ];
        });


        $stockMutations->setCollection($data);
        return $stockMutations;
    }
}
