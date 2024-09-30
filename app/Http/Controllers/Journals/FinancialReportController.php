<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Service\Journal\FinancialReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FinancialReportController extends Controller
{

    private FinancialReportService $financialReportService;

    public function __construct()
    {
        $this->financialReportService = new FinancialReportService();
    }

    public function index(): View
    {
        return view('pages.journals.financial-report.index');
    }


    public function getFixedAsset(): JsonResponse
    {
        return response()->json($this->financialReportService->getFixedAssets());
    }


    public function accumulatedDepreciationOfFixedAssetsAccount(): JsonResponse
    {
        return response()->json($this->financialReportService->accumulatedDepreciationOfFixedAssetsAccount());
    }

}
