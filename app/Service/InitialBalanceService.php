<?php

namespace App\Service;

use App\Models\AccountTransaction;
use Carbon\Carbon;

class InitialBalanceService
{
    private static int $perPage = 10;

    public function data()
    {
        $data = AccountTransaction::with('account')
            ->where('transaction_type', 'SA')
            ->orderBy('date')
            ->paginate(self::$perPage);

        return self::formattedData($data);
    }


    public function formattedData($accountTransactions)
    {
        $data = $accountTransactions->getCollection()->map(function ($accountTransaction) {
            return [
                'id' => $accountTransaction->id,
                'account' => $accountTransaction->account->code.' - '.$accountTransaction->account->name,
                'date' => Carbon::parse($accountTransaction->date)->format('Y'),
                'amount' => 'Rp.'.number_format($accountTransaction->amount, 2),
            ];
        });

        $accountTransactions->setCollection($data);
        return $accountTransactions;
    }
}