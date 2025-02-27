<?php

namespace App\Service\Transactions;

use AllowDynamicProperties;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Service\Accounts\AccountTransactionService;
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
        $data = Transaction::with('branch', 'unitType', 'debitAccount', 'creditAccount')
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
        $query = Transaction::with('branch', 'unitType', 'debitAccount', 'creditAccount');
        $filter = TransactionQueryFilter::apply($query, $request)
            ->paginate(self::$perPage);

        return self::formattedData($filter);
    }


    public function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $transactions = $data->getCollection()->map(callback: function ($item) {
            return [
                'id' => $item->id,
                'branch_id' => $item->branch_id,
                'date' => formatDate($item->date),
                'transaction_number' => $item->transaction_number,
                'debit' => $item->debitAccount->code . ' ' . $item->debitAccount->name,
                'debit_account_id' => $item->debitAccount->id,
                'credit_account_id' => $item->creditAccount->id,
                'credit' => $item->creditAccount->code . ' ' . $item->creditAccount->name,
                'detail' => $item->detail,
                'total_price' => 'Rp.' . number_format($item->total_price),
                'status' => $item->status
            ];
        });

        $data->setCollection($transactions);
        return $data;
    }

    public function store(TransactionRequest $request): void
    {
        Transaction::create([
            'transaction_number' => $this->generateTransactionNumber($request),
            'branch_id' => $request->input('branch_id'),
            'date' => $request->input('date'),
            'detail' => $request->input('detail'),
            'qty' => $request->input('qty'),
            'unit_type_id' => $request->input('unit_type_id'),
            'unit_price' => $request->input('unit_price'),
            'total_price' => $request->input('unit_price') * $request->input('qty'),
            'debit_account_id' => $request->input('debit_account_id'),
            'credit_account_id' => $request->input('credit_account_id'),
        ]);
    }


    public function update(TransactionRequest $request, Transaction $transaction): void
    {
        $transaction->update([
            'transaction_number' => $this->generateTransactionNumber($request),
            'branch_id' => $request->input('branch_id'),
            'date' => $request->input('date'),
            'detail' => $request->input('detail'),
            'qty' => $request->input('qty'),
            'unit_type_id' => $request->input('unit_type_id'),
            'unit_price' => $request->input('unit_price'),
            'total_price' => $request->input('unit_price') * $request->input('qty'),
            'debit_account_id' => $request->input('debit_account_id'),
            'credit_account_id' => $request->input('credit_account_id'),
        ]);
    }


    /**
     * @throws Throwable
     */
    public function confirm(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            $transaction->update([
                'status' => true
            ]);

            $this->accountTransactionService->createDebitTransaction(
                $transaction->branch_id,
                $transaction->detail,
                $transaction->debit_account_id,
                $transaction->total_price,
            );

            $this->accountTransactionService->createCreditTransaction(
                $transaction->branch_id,
                $transaction->detail,
                $transaction->credit_account_id,
                $transaction->total_price,
            );
        });
    }
}
