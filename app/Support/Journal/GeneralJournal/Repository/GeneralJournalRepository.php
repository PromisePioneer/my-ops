<?php

namespace App\Support\Journal\GeneralJournal\Repository;

use AllowDynamicProperties;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class GeneralJournalRepository
{


    public function __construct()
    {
        $this->accountingPeriod = new AccountingPeriod();
        $this->accountTransaction = new AccountTransaction();

    }


    public function data(Request $request)
    {
        return AccountTransaction::with('account.parent')
            ->whereHas('account', function ($query) use ($request) {
                $query->where('company_id', $request->session()->get('company_session'));
            })
            ->where('transaction_type', 'TR')
            ->whereYear('date', $this->accountingPeriod->query()->first()->year)
            ->orderBy('id');
    }

}
