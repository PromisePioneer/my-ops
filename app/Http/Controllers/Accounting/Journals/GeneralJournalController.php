<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Models\Master\Common\Branch;
use App\Support\Journal\GeneralJournal\Service\GeneralJournalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralJournalController extends Controller
{
    public int $perPage = 10;

    private GeneralJournalService $generalJournalService;
    private Branch $branch;

    public function __construct()
    {
        $this->generalJournalService = new GeneralJournalService();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.journals.general-journal.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->generalJournalService->data($request));
    }

    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->generalJournalService->filter($request));
    }
}
