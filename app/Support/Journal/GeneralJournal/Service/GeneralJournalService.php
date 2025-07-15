<?php

namespace App\Support\Journal\GeneralJournal\Service;

use AllowDynamicProperties;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use App\Support\Journal\GeneralJournal\Repository\GeneralJournalRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\currencyFormat;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class GeneralJournalService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->accountingPeriod = new AccountingPeriod();
        $this->generalJournalRepository = new GeneralJournalRepository();
    }


    public function data(Request $request)
    {
        $generalJournal = $this->generalJournalRepository->data($request)->cursor();
        return self::formattedData($generalJournal);
    }


    private static function formattedData($generalJournal)
    {
        return $generalJournal->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name,
                'date' => formatDate($item->date),
                'amount' => currencyFormat($item->amount),
                'type' => $item->entries_type,
                'account' => $item->account?->code . ' ' . $item->account?->name,
                'description' => $item->description,
            ];
        });
    }


    public function filter(Request $request)
    {

        $branch = $request->input('branch_id');
        $year = $request->input('year');
        $month = $request->input('month');

        $query = AccountTransaction::with('account.parent')
            ->whereHas('account', function ($query) use ($request) {
                $query->where('company_id', $request->session()->get('company_session'));
            })
            ->where('transaction_type', 'TR')
            ->whereYear('date', $this->accountingPeriod->query()->first()->year)
            ->orderBy('date');

        if ($branch) {
            $query->where('branch_id', $branch);
        }

        if ($year) {
            $query->whereYear('date', $year);
        }

        if ($month) {
            $query->whereMonth('date', $month);
        }


        $data = $query->cursor();
        return self::formattedData($data);
    }

}
