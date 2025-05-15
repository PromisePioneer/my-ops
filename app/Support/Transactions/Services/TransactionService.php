<?php

namespace App\Support\Transactions\Services;

use AllowDynamicProperties;
use App\Http\Requests\TransactionConfirmationRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\DraftStock;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Models\TaxSetting;
use App\Models\Transaction;
use App\Support\AccountTransactions\AccountTransactionService;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Transactions\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\currencyFormat;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class TransactionService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
        $this->handleUploadService = new HandleFileUploadService();
        $this->transactionRepository = new TransactionRepository();
    }

    public function generateTransactionNumber(Request $request): string
    {
        $latestTransaction = Transaction::where('branch_id', $request
            ->input('branch_id'))->latest()->first();
        $date = Carbon::parse($request->input('date'))->format('d');
        $month = Carbon::parse($request->input('date'))->format('m');
        $year = Carbon::parse($request->input('date'))->format('y');
        if ($latestTransaction && $date === "01") {
            $convertInvNumberToArray = $latestTransaction->transaction_number;
            $startingNumber = $convertInvNumberToArray[6] . $convertInvNumberToArray[7] . $convertInvNumberToArray[8];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $date . $month . $year . $startValue;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $date . $month . $year . $startValue;
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $data = $this->transactionRepository->getTransactions();
        $filter = TransactionACLFilter::apply($data, $request)->paginate(self::$perPage);
        return self::formattedData($filter);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = Transaction::search($search);
        $filter = TransactionACLFilter::apply($data, $request)->paginate(self::$perPage);
        return self::formattedData($filter);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = Transaction::with('branch', 'branch.parent', 'unitType', 'debitAccount', 'creditAccount', 'confirmedBy', 'approvedBy', 'createdBy');
        $filter = TransactionQueryFilter::apply($query, $request)
            ->paginate(self::$perPage);

        return self::formattedData($filter);
    }


    public function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $transactions = $data->getCollection()->map(callback: function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'branch_id' => $item->branch_id,
                'branch_name' => "{$item->branch->parent->name} - {$item?->branch?->name}",
                'date' => formatDate($item->date),
                'transaction_number' => $item->transaction_number,
                'item_name' => $item->item?->name,
                'qty' => $item->qty,
                'unit_type' => $item->item->unitType->name,
                'debit_account_id' => $item->debitAccount->id,
                'debit' => $item->debitAccount->code . ' ' . $item->debitAccount->name,
                'credit_account_id' => $item->creditAccount->id,
                'credit' => $item->creditAccount->code . ' ' . $item->creditAccount->name,
                'detail' => $item->detail,
                'total_price' => currencyFormat($item->total_price),
                'locked_status' => $item->locked_status,
                'status' => $item->status,
                'created_by' => $item->createdBy->name,
                'approved_by' => $item->approvedBy?->name,
                'attachment' => $item->attachment,
                'final_notes' => $item->final_notes,
            ];
        });

        $data->setCollection($transactions);
        return $data;
    }

    public function store(TransactionRequest $request): void
    {

        $formattedValue = str_replace('.', '', $request->input('unit_price'));
        $formattedValue = str_replace(',', '.', $formattedValue);
        $unitPrice = (float)$formattedValue;

        Transaction::create([
            'type' => $request->input('type'),
            'transaction_number' => $this->generateTransactionNumber($request),
            'branch_id' => $request->user()->branch_id ?? $request->input('branch_id'),
            'date' => $request->input('date'),
            'supplier_id' => $request->input('supplier_id'),
            'detail' => $request->input('detail'),
            'qty' => $request->input('qty'),
            'item_id' => $request->input('type') === 'Barang' ? $request->input('item_id') : null,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $request->input('qty'),
            'debit_account_id' => $request->input('debit_account_id'),
            'credit_account_id' => $request->input('credit_account_id'),
            'created_by' => $request->user()->id,
            'attachment' => $this->handleUploadService->upload(
                $request,
                'documents/transaction/item-transactions/',
                'attachment',
            ),
            'tax_invoice' => $this->handleUploadService->upload(
                $request,
                'documents/transaction/tax-invoice/',
                'tax_invoice',
            )
        ]);
    }


    public function update(TransactionRequest $request, Transaction $transaction): void
    {

        $formattedValue = str_replace('.', '', $request->input('unit_price'));
        $formattedValue = str_replace(',', '.', $formattedValue);
        $unitPrice = (float)$formattedValue;

        $transaction->update([
            'type' => $request->input('type'),
            'transaction_number' => $this->generateTransactionNumber($request),
            'branch_id' => $request->user()->branch_id ?? $request->input('branch_id'),
            'date' => $request->input('date'),
            'detail' => $request->input('detail'),
            'qty' => $request->input('qty'),
            'item_id' => $request->input('type') === 'Barang' ? $request->input('item_id') : null,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $request->input('qty'),
            'debit_account_id' => $request->input('debit_account_id'),
            'credit_account_id' => $request->input('credit_account_id'),
            'created_by' => $request->user()->id,
            'attachment' => $this->handleUploadService->upload(
                $request,
                'documents/transaction/item-transactions/',
                'attachment',
                $transaction->attachment
            ),
            'tax_invoice' => $this->handleUploadService->upload(
                $request,
                'documents/transaction/tax-invoice/',
                'tax_invoice',
                $transaction->tax_invoice,
            )
        ]);
    }


    /**
     * @throws Throwable
     */
    public function lockTransaction(Transaction $transaction): void
    {
        $transaction->update([
            'locked_status' => 1
        ]);
    }

    /**
     * @throws Throwable
     */
    public function confirm(Transaction $transaction, TransactionConfirmationRequest $request): void
    {
        DB::transaction(function () use ($transaction, $request) {
            $implodeID = implode(',', $request->get('id'));
            $explodeID = explode(',', $implodeID);
            $transaction->whereIn('id', $explodeID)->update([
                'status' => $request->status,
                'locked_status' => $request->status === 'Revisi' ? 0 : 1,
                'approved_by' => $request->status === 'Diterima' ?: $request->user()->id,
                'final_notes' => $request->input('final_notes'),
            ]);
            $ppnAccount = Account::where('code', '115-01')->first();
            $taxSetting = TaxSetting::where('name', 'PPN')->first();

            if ($request->input('status') === 'Diterima') {
                foreach ($explodeID as $transactionId) {
                    $transaction = Transaction::with('item.category', 'supplier')->where('id', $transactionId)->first();
                    $branch = Branch::with('parent')->where('id', $transaction->branch_id)->first();
                    $this->saveToStock($transaction);
                    $this->accountTransactionStore($taxSetting, $transaction, $branch, $ppnAccount);
                }
            }
        });
    }


    public function saveToStock($transaction): void
    {
        if ($transaction->item->category->name !== 'Kategori 4') {
            DraftStock::create([
                'transaction_id' => $transaction->id,
                'qty' => $transaction->qty
            ]);
        } else {
            Stock::create([
                'transaction_id' => $transaction->id,
                'branch_id' => $transaction->branch_id,
                'item_id' => $transaction->item_id,
                'qty' => $transaction->qty,
                'condition' => 'Baik'
            ]);
        }
    }


    public function accountTransactionStore($taxSetting, $transaction, $branch, $ppnAccount): void
    {
        $ppnTotal = ($taxSetting->rate / 100) * $transaction->total_price;


        $this->accountTransactionService->createDebitTransaction(
            $branch->parent->id,
            $transaction->detail,
            $transaction->debit_account_id,
            $transaction->total_price,
            $transaction->id,
        );

        $this->accountTransactionService->createCreditTransaction(
            $branch->parent->id,
            $transaction->detail,
            $transaction->credit_account_id,
            $transaction->total_price,
            $transaction->id
        );


        //ppn
        if ($transaction->supplier->tax_type === 'PKP') {
            $this->accountTransactionService->createDebitTransaction(
                $branch->parent->id,
                $transaction->detail,
                $ppnAccount->id,
                $ppnTotal,
                $transaction->id,
            );
        }
    }


    public function getItemTransactionQtyInThisMonth(): string
    {
        $data = Transaction::where('type', 'Barang')
            ->whereMonth('date', Carbon::now()->month)
            ->where('status', 'Diterima')
            ->sum('qty');

        return number_format($data, 2, '.', '.');
    }


}
