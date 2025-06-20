<?php

namespace App\Support\Master\Operational\InitialInventoryBalance\Service;

use AllowDynamicProperties;
use App\Http\Requests\InitialInventoryBalanceRequest;
use App\Models\AccountTransaction;
use App\Models\DraftStock;
use App\Models\InitialInventoryBalance;
use App\Models\Stock;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Master\Operational\InitialInventoryBalance\Repository\InitialInventoryBalanceRepository;
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
        $this->initialInventoryBalanceRepository = new InitialInventoryBalanceRepository();
        $this->handleUploadService = new HandleFileUploadService();
    }


    public function data(): LengthAwarePaginator
    {
        $initialInventoryBalances = $this->initialInventoryBalanceRepository->data()->paginate(self::$perPage);
        return self::formattedData($initialInventoryBalances);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->initialInventoryBalanceRepository->data();
        $filter = InitialInventoryBalanceQueryFilter::apply($query, $request);
        return self::formattedData($filter->paginate(self::$perPage));
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $initialInventoryBalances = $this->initialInventoryBalanceRepository->data();
        if (!empty($search)) {
            $initialInventoryBalances = $this->initialInventoryBalanceRepository->search($search);
        }

        return self::formattedData($initialInventoryBalances->paginate(self::$perPage));
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
                'stock_account' => "{$query->stockAccount->code} {$query->stockAccount->name}",
                'total_price' => currencyFormat($query->total_price),
                'detail' => $query->detail,
                'attachment' => $query->attachment,
                'status' => $query->status
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


        InitialInventoryBalance::create([
            'branch_id' => $request->branch_id,
            'date' => $request->input('date'),
            'contact_id' => $request->input('supplier_id'),
            'item_id' => $request->input('item_id'),
            'qty' => $request->input('qty'),
            'unit_price' => $unitPrice,
            'stock_account_id' => $request->input('stock_account_id'),
            'detail' => $request->input('detail'),
            'total_price' => $unitPrice * $request->input('qty'),
            'attachment' => $this->handleUploadService->upload(
                $request,
                'documents/initial-inventory-balance/attachment/',
                'attachment',
            ),
        ]);
    }


    public function update(InitialInventoryBalanceRequest $request, InitialInventoryBalance $initialInventoryBalance): void
    {
        $formattedValue = str_replace('.', '', $request->input('unit_price'));
        $formattedValue = str_replace(',', '.', $formattedValue);
        $unitPrice = (float)$formattedValue;


        $initialInventoryBalance->update([
            'branch_id' => $request->input('branch_id'),
            'date' => $request->input('date'),
            'contact_id' => $request->input('supplier_id'),
            'item_id' => $request->input('item_id'),
            'qty' => $request->input('qty'),
            'unit_price' => $unitPrice,
            'stock_account_id' => $request->input('stock_account_id'),
            'detail' => $request->input('detail'),
            'total_price' => $unitPrice * $request->input('qty'),
            'attachment' => $this->handleUploadService->upload(
                $request,
                'documents/initial-inventory-balance/attachment/',
                'attachment',
                $initialInventoryBalance->attachment
            ),
        ]);
    }


    /**
     * @throws Throwable
     */
    public function confirm(Request $request, InitialInventoryBalance $initialInventoryBalance): void
    {
        DB::transaction(function () use ($request, $initialInventoryBalance) {
            $implodeID = implode(',', $request->get('id'));
            $explodeID = explode(',', $implodeID);

            $query = $initialInventoryBalance->whereIn('id', $explodeID);
            $query->update(['status' => true]);

            $selectedInitialInventoryBalance = $query
                ->with('branch', 'supplier', 'item', 'stockAccount', 'branch.parent')
                ->get();
            foreach ($selectedInitialInventoryBalance as $item) {

                if ($item->item->category->name !== 'Kategori 4') {
                    DraftStock::create([
                        'initial_balance_inventory_id' => $item->id,
                        'qty' => $item->qty,
                        'qty_in_meter' => $item->qty_in_meter
                    ]);
                } else {
                    Stock::create([
                        'initial_balance_inventory_id' => $item->id,
                        'branch_id' => $item->branch_id,
                        'item_id' => $item->item_id,
                        'qty' => $item->qty,
                        'condition' => 'Baik'
                    ]);
                }


                AccountTransaction::create([
                    'branch_id' => $item->branch->parent->id,
                    'initial_inventory_balance_id' => $item->id,
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
