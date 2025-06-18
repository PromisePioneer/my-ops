<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\AssetDepreciation;
use App\Models\ItemCatalog;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Models\StockMutation;
use App\Models\StockMutationItem;
use App\Support\AccountTransactions\AccountTransactionService;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationRepository;
use App\Support\Master\Accounting\Assets\Service\AssetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class StockMutationService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->stockMutationRepository = new StockMutationRepository();
        $this->assetService = new  AssetService();
        $this->accountTransactionService = new  AccountTransactionService();
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
                'sender_signature' => $query->sender_signature,
            ];
        });


        $stockMutations->setCollection($data);
        return $stockMutations;
    }


    /**
     * @throws Throwable
     */
    public function store(StockMutationRequest $request): void
    {
        DB::transaction(function () use ($request) {

            $senderBranchId = $request->user()->branch_id ?? Branch::where('name', 'Dumai')->first()->id;

            $stockMutation = StockMutation::create([
                'stock_mutation_number' => GenerateStockMutationNumber::apply($senderBranchId, Carbon::now()->format('Y-m-d')),
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
        if (session()->has('stock_mutation_items')) {
            $totalDepreciation = 0;
            $itemName = null;
            $itemCatalog = null;
            foreach (session('stock_mutation_items') as $item) {
                if (!empty($item['code'])) {
                    $itemCatalog = ItemCatalog::with('asset', 'stock')
                        ->where('code', $item['code'])
                        ->first();


                    if (!empty($itemCatalog->asset_id)) {
                        $itemCatalog->asset->update([
                            'branch_id' => $request->to_branch
                        ]);


                        $itemName = $itemCatalog->stock->transaction?->item?->name ?? $itemCatalog->stock->initialInventoryBalance->item->name;
                        $totalDepreciation = AssetDepreciation::where('asset_id', $itemCatalog->asset_id)
                            ->whereBetween('depreciation_date', [
                                Carbon::parse($itemCatalog->asset->date)->format('Y-m-d'),
                                Carbon::now()->format('Y-m-d')
                            ])
                            ->sum('depreciation_amount');

                    }

                    $oldStock = Stock::where('id', $item['stock_id'])
                        ->where('branch_id', $request->from_branch)
                        ->first();
                    $newStock = Stock::where('id', $item['stock_id'])
                        ->where('branch_id', $request->to_branch)
                        ->first();

                    if ($newStock) {
                        $oldStock->decrement('available_qty', $item['qty']);
                        $newStock->increment('available_qty', $item['qty']);
                        $itemCatalog->update([
                            'stock_id' => $newStock->id,
                        ]);
                    } else {
                        $stock = Stock::create([
                            'branch_id' => $request->to_branch,
                            'transaction_id' => $oldStock->transaction_id,
                            'initial_balance_inventory_id' => $oldStock->initial_balance_inventory_id,
                            'on_hold_qty' => 0,
                            'available_qty' => $item['qty'],
                            'broken_qty' => 0,
                        ]);
                        $oldStock->decrement('available_qty', $item['qty']);
                        $itemCatalog->update([
                            'stock_id' => $stock->id,
                        ]);
                    }


                    StockMutationItem::create([
                        'stock_mutation_id' => $stockMutation->id,
                        'stock_id' => $item['stock_id'],
                        'code' => $item['code'],
                        'qty' => $item['qty'],
                    ]);
                }

                if (empty($item['code'])) {
                    $oldStock = Stock::where('id', $item['stock_id'])
                        ->where('branch_id', $request->from_branch)
                        ->first();
                    $newStock = Stock::where('id', $item['stock_id'])
                        ->where('branch_id', $request->to_branch)
                        ->first();


                    if ($newStock) {
                        $newStock->increment('available_qty', $item['qty']);
                    } else {
                        Stock::create([
                            'branch_id' => $request->to_branch,
                            'transaction_id' => $oldStock->transaction_id,
                            'initial_balance_inventory_id' => $oldStock->initial_balance_inventory_id,
                            'on_hold_qty' => 0,
                            'available_qty' => $item['qty'],
                            'broken_qty' => 0,
                        ]);
                    }

                    StockMutationItem::create([
                        'stock_mutation_id' => $stockMutation->id,
                        'stock_id' => $item['stock_id'],
                        'code' => $item['code'],
                        'qty' => $item['qty'],
                    ]);
                }
            }

            $fromBranch = Branch::find($request->from_branch)->parent_id;
            $toBranch = Branch::find($request->to_branch)->parent_id;
            // debit old branch
            $this->accountTransactionService->createDebitTransaction(
                Branch::find($request->from_branch)->parent_id,
                "MUTASI {$itemName} DARI {$fromBranch} KE {$toBranch}",
                $itemCatalog->stock->transaction->credit_account_id,
                $itemCatalog->stock->transaction->unit_price,
            );
            $this->accountTransactionService->createDebitTransaction(
                Branch::find($request->from_branch)->parent_id,
                "Akumulasi Penyusutan Aset {$itemName}",
                $itemCatalog->stock->transaction->credit_account_id,
                $totalDepreciation,
            );

        }
        session()->forget('stock_mutation_items');
    }

}
