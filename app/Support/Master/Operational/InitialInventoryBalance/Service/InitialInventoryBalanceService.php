<?php

namespace App\Support\Master\Operational\InitialInventoryBalance\Service;

use AllowDynamicProperties;
use App\Enum\Transaction\TransactionType;
use App\Http\Requests\InitialInventoryBalanceRequest;
use App\Models\AccountTransaction;
use App\Models\DraftStock;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Models\Transaction;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Inventory\StockManagement\DraftStock\Repository\DraftStockRepository;
use App\Support\Master\Accounting\InitialBalances\Repositories\InitialBalanceRepository;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use App\Support\Master\Operational\InitialInventoryBalance\Repository\InitialInventoryBalanceRepository;
use App\Support\Transactions\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\currencyFormat;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class InitialInventoryBalanceService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
        $this->transaction = new Transaction();
        $this->branchRepository = new BranchRepository();
        $this->handleUploadService = new HandleFileUploadService();
        $this->initialInventoryBalanceRepository = new InitialInventoryBalanceRepository();
        $this->draftStock = new DraftStock();
        $this->stock = new Stock();
        $this->accountTransaction = new AccountTransaction();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $initialInventoryBalances = $this->transactionRepository->getInitialInventoryBalance($request);
        $aclFilter = InitialInventoryBalanceACLFilter::apply($initialInventoryBalances, $request);
        return self::formattedData($aclFilter->paginate(self::$perPage));
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->transactionRepository->getInitialInventoryBalance($request);
        $filter = InitialInventoryBalanceQueryFilter::apply($query, $request);
        $aclFilter = InitialInventoryBalanceACLFilter::apply($filter, $request);
        return self::formattedData($aclFilter->paginate(self::$perPage));
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $branch = $request->user()->branch_id;
        if (!empty($branch)) {
            $branch = $this->branchRepository
                ->findById($request->user()->branch_id)
                ->children
                ->pluck('id')
                ->toArray();
        }

        $itemCollections = Transaction::search($search)->query(function ($query) use ($search, $request, $branch) {
            $this->initialInventoryBalanceRepository->search($request, $query, $branch);
        })->paginate(self::$perPage);

        return self::formattedData($itemCollections);
    }


    public function formattedData(LengthAwarePaginator $initialInventoryBalances): LengthAwarePaginator
    {
        $data = $initialInventoryBalances->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'branch_name' => "{$query->branch->name} - {$query->branch->parent->name}",
                'date' => formatDate($query->date),
                'supplier_name' => $query->supplier->name,
                'item_name' => $query->item->name,
                'qty' => $query->qty,
                'unit_price' => $query->unit_price,
                'unit_type' => $query->item->unitType->name,
                'stock_account' => "{$query->stockAccount?->code} {$query->stockAccount?->name}",
                'total_price' => currencyFormat($query->total_price),
                'detail' => $query->detail,
                'attachment' => $query->attachment,
                'status' => $query->status,
                'qty_in_meter' => $query->qty_in_meter,
                'transaction_number' => $query->transaction_number,
            ];
        });


        $initialInventoryBalances->setCollection($data);
        return $initialInventoryBalances;
    }


    public function store(InitialInventoryBalanceRequest $request): void
    {
        $formattedValue = str_replace('.', '', $request->input('unit_price'));
        $formattedValue = str_replace(',', '.', $formattedValue);
        $unitPrice = (float)$formattedValue;


        $this->transaction->create([
            'branch_id' => $request->branch_id,
            'date' => $request->input('date'),
            'contact_id' => $request->input('supplier_id'),
            'item_id' => $request->input('item_id'),
            'qty' => $request->input('qty'),
            'unit_price' => $unitPrice,
            'type' => TransactionType::INITIAL_INVENTORY_BALANCE->value,
            'stock_account_id' => $request->input('stock_account_id'),
            'detail' => $request->input('detail'),
            'total_price' => $unitPrice * $request->input('qty'),
            'qty_in_meter' => $request->input('qty_in_meter'),
            'attachment' => $this->handleUploadService->upload(
                $request,
                'documents/initial-inventory-balance/attachment/',
                'attachment',
            ),
            'locked_status' => true,
            'created_by' => auth()->id(),
        ]);
    }


    public function update(InitialInventoryBalanceRequest $request, Transaction $transaction): void
    {
        $formattedValue = str_replace('.', '', $request->input('unit_price'));
        $formattedValue = str_replace(',', '.', $formattedValue);
        $unitPrice = (float)$formattedValue;


        $transaction->update([
            'branch_id' => $request->input('branch_id'),
            'date' => $request->input('date'),
            'contact_id' => $request->input('supplier_id'),
            'item_id' => $request->input('item_id'),
            'qty' => $request->input('qty'),
            'unit_price' => $unitPrice,
            'stock_account_id' => $request->input('stock_account_id'),
            'detail' => $request->input('detail'),
            'total_price' => $unitPrice * $request->input('qty'),
            'qty_in_meter' => $request->input('qty_in_meter'),
            'attachment' => $this->handleUploadService->upload(
                $request,
                'documents/initial-inventory-balance/attachment/',
                'attachment',
                $transaction->attachment
            ),
        ]);
    }


    /**
     * @throws Throwable
     */
    public function confirm(Request $request, Transaction $transaction): void
    {
        DB::transaction(function () use ($request, $transaction) {
            $query = $transaction->whereIn('id', $request->get('id'));
            $query->update(['status' => true]);
            $selectedInitialInventoryBalance = $query->with([
                'branch',
                'supplier',
                'item',
                'stockAccount',
                'branch.parent'
            ])->get();

            foreach ($selectedInitialInventoryBalance as $initialInventoryBalance) {
                if ($initialInventoryBalance->item->category->name !== 'Kategori 4') {
                    $this->draftStock->create([
                        'transaction_id' => $initialInventoryBalance->id,
                        'qty' => $initialInventoryBalance->qty,
                        'qty_in_meter' => $initialInventoryBalance->qty_in_meter
                    ]);
                } else {
                    $this->stock->create([
                        'transaction_id' => $initialInventoryBalance->id,
                        'branch_id' => $initialInventoryBalance->branch_id,
                        'available_qty' => $initialInventoryBalance->qty,
                        'on_hold_qty' => 0,
                        'broken_qty' => 0
                    ]);
                }

                $this->accountTransaction->create([
                    'branch_id' => $initialInventoryBalance->branch->parent->id,
                    'transaction_id' => $initialInventoryBalance->id,
                    'date' => $initialInventoryBalance->date,
                    'account_id' => $initialInventoryBalance->stock_account_id,
                    'description' => $initialInventoryBalance->detail,
                    'transaction_type' => 'SA',
                    'entries_type' => 'debit',
                    'amount' => $initialInventoryBalance->total_price,
                ]);
            }
        });
    }


}
