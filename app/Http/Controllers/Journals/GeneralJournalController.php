<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralJournalController extends Controller
{
    public int $perPage = 10;
    private AccountTransaction $accountTransaction;

    public function __construct()
    {
        $this->accountTransaction = new AccountTransaction();
    }
    public function index()
    {
        return view('pages.journals.general-journal.index');
    }

    public function period(): JsonResponse
    {
        $generalJournal = $this->accountTransaction->getGeneralJournalPeriodBasedOnUserBranch($this->perPage);
        return response()->json($generalJournal);
    }

    public function detail(Request $request, $time)
    {
        $month = date('m', strtotime($time));
        $year = date('Y', strtotime($time));

        $generalJournal = $this->accountTransaction->getGeneralJournalDataBasedOnUserBranchAndPeriod($month, $year);
        return view('pages.journals.general-journal.detail', compact('generalJournal', 'time'));
    }


    public function detailJournal($time): JsonResponse
    {
        $month = date('m', strtotime($time));
        $year = date('Y', strtotime($time));

        $generalJournal = $this->accountTransaction->getGeneralJournalDataDetails($month, $year);
        return response()->json($generalJournal);
    }
}
