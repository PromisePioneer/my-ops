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
    private static int $perPage = 100;

    public function __construct()
    {
        $this->accountingPeriod = new AccountingPeriod();
        $this->generalJournalRepository = new GeneralJournalRepository();
    }


    public function data(Request $request)
    {
        $generalJournal = $this->generalJournalRepository
            ->data($request)
            ->paginate(self::$perPage);
        return self::formattedData($generalJournal);
    }


    public function filter(Request $request)
    {

        $query = $this->generalJournalRepository->data($request);
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData($generalJournal)
    {
        $data = $generalJournal->getCollection()->map(function ($item) {
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


        $generalJournal->setCollection($data);
        return $generalJournal;
    }


}
