<?php

namespace App\Support\Transactions;

use AllowDynamicProperties;
use App\Http\Requests\TransactionConfirmationRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Stock;
use App\Models\Transaction;
use App\Support\AccountTransactions\AccountTransactionService;
use App\Support\HelperService\HandleFileUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class TransactionService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
        $this->handleUploadService = new HandleFileUploadService();
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


    public function data(): LengthAwarePaginator
    {
        $data = Transaction::with('branch', 'unitType', 'debitAccount', 'creditAccount', 'item')
            ->paginate(self::$perPage);

        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = Transaction::search($search)->query(function ($query) {
            $query->with('transactionType', 'transactionType.debitAccount', 'transactionType.creditAccount');
        })->paginate(self::$perPage);

        return self::formattedData($data);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = Transaction::with('branch', 'unitType', 'debitAccount', 'creditAccount', 'confirmedBy', 'approvedBy', 'createdBy');
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
                'date' => formatDate($item->date),
                'transaction_number' => $item->transaction_number,
                'item_name' => $item->item?->name,
                'debit_account_id' => $item->debitAccount->id,
                'debit' => $item->debitAccount->code . ' ' . $item->debitAccount->name,
                'credit_account_id' => $item->creditAccount->id,
                'credit' => $item->creditAccount->code . ' ' . $item->creditAccount->name,
                'detail' => $item->detail,
                'total_price' => 'Rp.' . number_format($item->total_price),
                'locked_status' => $item->locked_status,
                'confirmation_status' => $item->confirmation_status,
                'confirmation_excuses' => $item->confirmation_excuses,
                'final_status' => $item->final_status,
                'final_excuses' => $item->final_excuses,
                'confirmed_by' => $item->confirmedBy?->name,
                'created_by' => $item->createdBy->name,
                'attachment' => $item->attachment,

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
        ]);
    }


    public function update(TransactionRequest $request, Transaction $transaction): void
    {
        $transaction->update([
            'type' => $request->input('type'),
            'transaction_number' => $this->generateTransactionNumber($request),
            'branch_id' => $request->user()->branch_id ?? $request->input('branch_id'),
            'date' => $request->input('date'),
            'detail' => $request->input('detail'),
            'qty' => $request->input('qty'),
            'item_id' => $request->input('type') === 'Barang' ? $request->input('item_id') : null,
            'unit_price' => $request->input('unit_price'),
            'total_price' => $request->input('unit_price') * $request->input('qty'),
            'debit_account_id' => $request->input('debit_account_id'),
            'credit_account_id' => $request->input('credit_account_id'),
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
            $transaction->update([
                'confirmation_status' => $request->confirmation_status,
                'locked_status' => $request->confirmation_status === 'Revisi' ? 0 : 1,
                'confirmed_by' => $request->confirmation_status === 'Diterima' ?: $request->user()->id,
                'confirmation_excuses' => $request->input('confirmation_excuses'),
            ]);

            if ($request->input('confirmation_status') === 'Diterima') {
                if ($transaction->type === 'Barang') {
                    $stocks = Stock::where('item_id', $transaction->item_id)
                        ->where('transaction_id', $transaction->id)
                        ->where('branch_id', $transaction->branch_id);

                    if ($stocks->exists()) {
                        $stocks->update([
                            'qty' => $stocks->first()->qty + $transaction->qty
                        ]);
                    } else {
                        Stock::create([
                            'branch_id' => $transaction->branch_id,
                            'transaction_id' => $transaction->id,
                            'item_id' => $transaction->item_id,
                            'qty' => $transaction->qty
                        ]);
                    }
                }


                $this->accountTransactionService->createDebitTransaction(
                    $transaction->branch_id,
                    $transaction->detail,
                    $transaction->debit_account_id,
                    $transaction->total_price,
                    $transaction->id,
                );

                $this->accountTransactionService->createCreditTransaction(
                    $transaction->branch_id,
                    $transaction->detail,
                    $transaction->credit_account_id,
                    $transaction->total_price,
                    $transaction->id
                );
            }
        });
    }


}
