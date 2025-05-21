<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Models\StockMutation;
use App\Models\StockMutationItem;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
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
                'new_branch_name' => $query->newBranch->parent->name,
                'sender_name' => $query->sender->name,
                'receiver_name' => $query->receiver->name,
            ];
        });


        $stockMutations->setCollection($data);
        return $stockMutations;
    }


    /**
     * @throws \Throwable
     */
    public function store(StockMutationRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $stockMutation = StockMutation::create([
                'date' => Carbon::now()->format('Y-m-d'),
                'old_branch_id' => $request->input('from_branch'),
                'new_branch_id' => $request->input('to_branch'),
                'sender_id' => $request->user()->id,
                'receiver_id' => $request->input('receiver_id'),
                'description' => $request->input('description')
            ]);

            $this->stockMutationItemStore($request, $stockMutation);
        });

    }


    public function stockMutationItemStore(StockMutationRequest $request, StockMutation $stockMutation): void
    {
        if ($request->has('itemWithCodeFields')) {
            foreach ($request->itemWithCodeFields as $value) {
                $itemCatalog = ItemCatalog::find($value);
                StockMutationItem::create([
                    'stock_mutation_id' => $stockMutation->id,
                    'stock_id' => $itemCatalog->id,
                    'code' => $itemCatalog->code,
                    'qty' => 1,
                ]);
            }
        }

        if ($request['itemWithoutCodeFields']) {
            foreach ($request['itemWithoutCodeFields'] as $key => $value) {
                $stockWithoutCode = Stock::with('item', 'itemCatalog')
                    ->where('id', $value['stock_id'])
                    ->first();
                $value['stock_mutation_id'] = $stockMutation->id;
                $value['stock_id'] = $stockWithoutCode->id;
                StockWithdrawalItem::create($value);
            }
        }
    }
}
