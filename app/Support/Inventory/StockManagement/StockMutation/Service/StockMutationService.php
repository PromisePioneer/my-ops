<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Models\ItemCatalog;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Models\StockMutation;
use App\Models\StockMutationItem;
use App\Models\Transaction;
use App\Support\AccountTransactions\Service\AccountTransactionService;
use App\Support\Inventory\StockManagement\DraftStock\Repository\ItemCatalogRepository;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationItemRepository;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationRepository;
use App\Support\Master\Accounting\Accounts\Repositories\AccountRepository;
use App\Support\Master\Accounting\Assets\Repositories\AssetDepreciationRepository;
use App\Support\Master\Accounting\Assets\Repositories\AssetRepository;
use App\Support\Master\Accounting\Assets\Service\AssetService;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class StockMutationService
{
    private static int $perPage = 10;
    private const string ITEM_MUTATION_DESCRIPTION = 'Mutasi %s Dari %s Ke %s';
    private const string ACCUMULATED_DEPRECIATION_OF_ASSET_DESCRIPTION = 'Akumulasi Penyusutan Aset %s';


    public function __construct()
    {
        $this->stockMutationRepository = new StockMutationRepository();
        $this->assetService = new  AssetService();
        $this->accountTransactionService = new  AccountTransactionService();
        $this->itemCatalogRepository = new ItemCatalogRepository();
        $this->stockRepository = new StockRepository();
        $this->stockMutationItemRepository = new StockMutationItemRepository();
        $this->stock = new Stock();
        $this->itemCatalog = new ItemCatalog();
        $this->accountRepository = new AccountRepository();
        $this->branchRepository = new BranchRepository();
        $this->assetDepreciationRepository = new AssetDepreciationRepository();
        $this->assetRepository = new AssetRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $stockMutations = $this->stockMutationRepository->getData()->paginate(self::$perPage);
        return self::formattedData($stockMutations);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $stockMutations = $this->stockMutationRepository->getData();
        $search = $request->input('search');
        if (!empty($search)) {
            $stockMutations->whereHas('sender', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('receiver', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('stock_mutation_number', 'like', '%' . $search . '%');
        }

        $query = $stockMutations->paginate(self::$perPage);
        return self::formattedData($query);
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
                'old_branch_name' => "{$query->oldBranch->parent->name} ({$query->oldBranch->name})",
                'new_branch_name' => "{$query->newBranch->parent->name} ({$query->newBranch->name})",
                'sender_name' => $query->sender->name,
                'receiver_name' => $query->receiver->name,
                'sender_signature' => $query->sender_signature,
                'status' => $query->status,
                'items' => $query->stockMutationItems->map(function ($item) {
                    $item->load('stock.transaction.item', 'itemCatalog');
                    return [
                        'id' => $item->id,
                        'code' => $item->itemCatalog?->code ?? null,
                        'name' => $item->stock->transaction->item->name,
                        'qty' => "$item->qty {$item->stock->transaction->item->unitType->name}",
                    ];
                })
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
                'description' => $request->input('description'),
                'status' => 'Dikirim',
            ]);

            $this->stockMutationItemStore($request, $stockMutation);
        });

    }


    public function stockMutationItemStore(StockMutationRequest $request, StockMutation $stockMutation): void
    {
        if (session()->has('stock_mutation_items')) {
            foreach (session('stock_mutation_items') as $item) {
                $stock = $this->stock->query()->find($item['stock_id']);
                if (!empty($item['code'])) {
                    $itemCatalog = $this->itemCatalogRepository->findByCode($item['code'])->first();
                    $itemCatalog->update([
                        'status' => 'Proses Mutasi'
                    ]);
                }

                StockMutationItem::create([
                    'stock_mutation_id' => $stockMutation->id,
                    'stock_id' => $item['stock_id'],
                    'code' => $item['code'],
                    'qty' => $item['qty'],
                ]);
                $stock->increment('on_hold_qty', $item['qty']);
                $stock->decrement('available_qty', $item['qty']);
            }
        }
        session()->forget('stock_mutation_items');
    }


    public function receive(StockMutation $stockMutation): void
    {
        DB::transaction(function () use ($stockMutation) {
            $stockMutation->update([
                'status' => 'Diterima'
            ]);
            $stockMutationItems = $this->stockMutationItemRepository->getByStockMutationId($stockMutation->id)->get();
            foreach ($stockMutationItems as $stockMutationItem) {
                $stock = $this->stock->query()->with('transaction', 'initialInventoryBalance')->find($stockMutationItem->stock_id);
                $oldStock = $this->stockRepository->findByStockIdAndBranchId($stockMutationItem->stock_id, $stockMutation->old_branch_id)->first();
                $newStock = $this->stockRepository->findByTransactionIdAndBranch(
                    $stock->transaction?->id,
                    $stock->initialInventoryBalance?->id,
                    $stockMutation->new_branch_id
                )->first();
                if (!empty($newStock)) {
                    $newStock->increment('available_qty', $stockMutationItem->qty);
                } else {

                    $newStock = $this->stock->query()->create([
                        'branch_id' => $stockMutation->new_branch_id,
                        'transaction_id' => $oldStock->transaction_id,
                        'initial_balance_inventory_id' => $oldStock->initial_balance_inventory_id,
                        'on_hold_qty' => 0,
                        'available_qty' => $stockMutationItem->qty,
                        'broken_qty' => 0,
                    ]);
                }

                if (!empty($stockMutationItem->code)) {
                    $itemCatalog = $this->itemCatalogRepository
                        ->findByCode($stockMutationItem->code)
                        ->first();
                    $itemCatalog->update([
                        'stock_id' => $newStock->stock_id ?? $newStock->id,
                        'status' => 'Tersedia'
                    ]);
                    $oldStock->decrement('on_hold_qty', $stockMutationItem->qty);
                    $this->accountTransaction($itemCatalog, $stockMutation);
                }
            }
        });
    }


    public function accountTransaction(ItemCatalog $itemCatalog, StockMutation $stockMutation): void
    {
        $transaction = $itemCatalog->stock->transaction;
        $initialInventoryBalance = $itemCatalog->stock->initialInventoryBalance;
        $transactionDebitAccountId = $this->accountRepository->findById($itemCatalog->stock->transaction?->credit_account_id ?? $itemCatalog->stock->initialInventoryBalance->stock_account_id);
        $accumulatedDepreciationOfAssetAccountId = $this->accountRepository->findByCode('130')->first();
        $asset = $this->assetRepository->findById($itemCatalog->asset_id);
        $this->debitTransaction($stockMutation, $itemCatalog, $transaction, $initialInventoryBalance, $transactionDebitAccountId, $accumulatedDepreciationOfAssetAccountId, $asset);
        $this->creditTransaction($stockMutation, $itemCatalog, $transaction, $initialInventoryBalance, $transactionDebitAccountId, $accumulatedDepreciationOfAssetAccountId, $asset);
    }


    private function debitTransaction(
        StockMutation $stockMutation,
        ItemCatalog   $itemCatalog,
        ?Transaction  $transaction,
                      $initialInventoryBalance,
        Account       $transactionDebitAccountId,
        Account       $accumulatedDepreciationOfAssetAccountId,
        Asset         $asset
    ): void
    {
        $unitPrice = $transaction->unit_price ?? $initialInventoryBalance?->unit_price;
        // cabang awal
        $this->accountTransactionService->createDebitTransaction(
            $this->branchRepository->findById($stockMutation->old_branch_id)->parent->id,
            sprintf(self::ITEM_MUTATION_DESCRIPTION,
                $transaction?->item?->name ?? $initialInventoryBalance?->item->name,
                $this->branchRepository->findById($stockMutation->old_branch_id)->parent->name,
                $this->branchRepository->findById($stockMutation->new_branch_id)->parent->name,
            ),
            $transactionDebitAccountId->id,
            $transaction?->unit_price ?? $initialInventoryBalance?->unit_price,
        );
        if (!empty($itemCatalog->asset_id)) {
            $this->accountTransactionService->createDebitTransaction(
                $this->branchRepository->findById($stockMutation->old_branch_id)->parent->id,
                sprintf(self::ACCUMULATED_DEPRECIATION_OF_ASSET_DESCRIPTION, $asset->item->name),
                $accumulatedDepreciationOfAssetAccountId->id,
                $this->assetDepreciationRepository->getSumDepreciationAmount($itemCatalog->asset_id, $asset->date, date('Y-m-d')),
            );
        }

        // cabang tujuan
        $totalAmount = $this->assetDepreciationRepository->getSumDepreciationAmount(
                $itemCatalog->asset_id,
                $asset->date,
                date('Y-m-d')
            ) + $unitPrice;
        $this->accountTransactionService->createDebitTransaction(
            $this->branchRepository->findById($stockMutation->new_branch_id)->parent->id,
            sprintf(self::ITEM_MUTATION_DESCRIPTION,
                $transaction?->item?->name ?? $initialInventoryBalance?->item?->name,
                $this->branchRepository->findById($stockMutation->old_branch_id)->parent->name,
                $this->branchRepository->findById($stockMutation->new_branch_id)->parent->name,
            ),
            $transaction->credit_account_id ?? $initialInventoryBalance->stock_account_id,
            !empty($itemCatalog->asset_id) ? $totalAmount : $transaction->unit_price,
        );
    }

    private function creditTransaction(
        StockMutation $stockMutation,
        ItemCatalog   $itemCatalog,
        ?Transaction  $transaction,
                      $initialInventoryBalance,
        Account       $transactionDebitAccountId,
        Account       $accumulatedDepreciationOfAssetAccountId,
        Asset         $asset
    ): void
    {
        //cabang awal
        $unitPrice = $transaction->unit_price ?? $initialInventoryBalance?->unit_price;
        $totalAmount = $this->assetDepreciationRepository->getSumDepreciationAmount($itemCatalog->asset_id, $asset->date, date('Y-m-d')) + $unitPrice;
        $this->accountTransactionService->createCreditTransaction(
            $this->branchRepository->findById($stockMutation->old_branch_id)->parent->id,
            sprintf(self::ITEM_MUTATION_DESCRIPTION,
                $transaction?->item?->name ?? $initialInventoryBalance?->item->name,
                $this->branchRepository->findById($stockMutation->old_branch_id)->parent->name,
                $this->branchRepository->findById($stockMutation->new_branch_id)->parent->name,
            ),
            $transaction->credit_account_id ?? $initialInventoryBalance->stock_account_id,
            !empty($itemCatalog->asset_id) ? $totalAmount : $unitPrice,
        );

        //cabang tujuan
        $this->accountTransactionService->createCreditTransaction(
            $this->branchRepository->findById($stockMutation->new_branch_id)->parent->id,
            sprintf(self::ITEM_MUTATION_DESCRIPTION,
                $transaction?->item?->name ?? $initialInventoryBalance?->item?->name,
                $this->branchRepository->findById($stockMutation->old_branch_id)->parent->name,
                $this->branchRepository->findById($stockMutation->new_branch_id)->parent->name,
            ),
            $transactionDebitAccountId->id,
            $transaction->unit_price ?? $initialInventoryBalance?->unit_price,
        );
        if (!empty($itemCatalog->asset_id)) {
            $this->accountTransactionService->createCreditTransaction(
                $this->branchRepository->findById($stockMutation->new_branch_id)->parent->id,
                sprintf(self::ACCUMULATED_DEPRECIATION_OF_ASSET_DESCRIPTION, $asset->item->name),
                $accumulatedDepreciationOfAssetAccountId->id,
                $this->assetDepreciationRepository->getSumDepreciationAmount($itemCatalog->asset_id, $asset->date, date('Y-m-d')),
            );
        }
    }

}
