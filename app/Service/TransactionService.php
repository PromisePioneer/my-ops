<?php

namespace App\Service;

use AllowDynamicProperties;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\TransactionType;
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
        $data = Transaction::with('transactionType', 'transactionType.debitAccount', 'transactionType.creditAccount')
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


    public function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $transactions = $data->getCollection()->map(callback: function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'transaction_number' => $item->transaction_number,
                'transaction_type' => $item->transactionType->name,
                'debit' => $item->transactionType->debitAccount->code . ' ' . $item->transactionType->debitAccount->name,
                'debit_account_id' => $item->transactionType->debitAccount->id,
                'credit_account_id' => $item->transactionType->creditAccount->id,
                'credit' => $item->transactionType->creditAccount->code . ' ' . $item->transactionType->creditAccount->name,
                'name' => $item->name,
                'detail' => $item->detail,
                'amount' => 'Rp.' . number_format($item->amount, 2),
                'status' => $item->status
            ];
        });

        $data->setCollection($transactions);
        return $data;
    }

    public function store(TransactionRequest $request): void
    {
        Transaction::create([
            'branch_id' => $request->branch_id,
            'transaction_number' => $this->generateTransactionNumber($request),
            'transaction_type_id' => $request->transaction_type_id,
            'date' => $request->date,
            'detail' => $request->detail,
            'amount' => $request->amount,
        ]);
    }


    public function update(TransactionRequest $request, Transaction $transaction): void
    {
        $transaction->update([
            'branch_id' => $request->branch_id,
            'transaction_number' => $this->generateTransactionNumber($request),
            'transaction_type_id' => $request->transaction_type_id,
            'date' => $request->date,
            'detail' => $request->detail,
            'amount' => $request->amount,
        ]);
    }


    /**
     * @throws Throwable
     */
    public function confirm(Transaction $transaction): void
    {
        $transactionType = TransactionType::with('debitAccount', 'creditAccount')->where('id', $transaction->transaction_type_id)->first();
        DB::transaction(function () use ($transaction, $transactionType) {
            $this->accountTransactionService->createDebitTransaction(
                $transaction->branch_id,
                $transaction->detail,
                $transactionType->debitAccount->id,
                $transaction->amount,
            );

            $this->accountTransactionService->createCreditTransaction(
                $transaction->branch_id,
                $transaction->detail,
                $transactionType->creditAccount->id,
                $transaction->amount,
            );
        });
    }
}
