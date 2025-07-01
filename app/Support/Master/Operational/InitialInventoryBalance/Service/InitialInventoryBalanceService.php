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
        $this->handleUploadService = new HandleFileUploadService();
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
            $branch = Branch::with('children')
                ->find($request->user()->branch_id)
                ->children
                ->pluck('id')
                ->toArray();
        }

        $itemCollections = Transaction::search($search)->query(function ($query) use ($search, $request, $branch) {
            if (!empty($request->user()->branch_id)) {
                $query->whereIn('branch_id', $branch);
            }
            $query->where('transactions.type', TransactionType::INITIAL_INVENTORY_BALANCE->value)
                ->join('branches', 'transactions.branch_id', 'branches.id')
                ->join('branches as parent_branches', 'parent_branches.id', '=', 'branches.parent_id')
                ->join('contacts', 'transactions.contact_id', '=', 'contacts.id')
                ->join('item_collections', 'transactions.item_id', '=', 'item_collections.id')
                ->select('transactions.*', 'parent_branches.name', 'item_collections.name');
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


        Transaction::create([
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
            $implodeID = implode(',', $request->get('id'));
            $explodeID = explode(',', $implodeID);

            $query = $transaction->whereIn('id', $explodeID);
            $query->update(['status' => true]);

            $selectedInitialInventoryBalance = $query
                ->with(['branch', 'supplier', 'item', 'stockAccount', 'branch.parent'])
                ->get();
            foreach ($selectedInitialInventoryBalance as $item) {

                if ($item->item->category->name !== 'Kategori 4') {
                    DraftStock::create([
                        'transaction_id' => $item->id,
                        'qty' => $item->qty,
                        'qty_in_meter' => $item->qty_in_meter
                    ]);
                } else {
                    Stock::create([
                        'transaction_id' => $item->id,
                        'branch_id' => $item->branch_id,
                        'available_qty' => $item->qty,
                        'on_hold_qty' => 0,
                        'broken_qty' => 0
                    ]);
                }


                AccountTransaction::create([
                    'branch_id' => $item->branch->parent->id,
                    'transaction_id' => $item->id,
                    'date' => $item->date,
                    'account_id' => $item->stock_account_id,
                    'description' => $item->detail,
                    'transaction_type' => 'SA',
                    'entries_type' => 'debit',
                    'amount' => $item->total_price,
                ]);
            }

        });

    }


}
