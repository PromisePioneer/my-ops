<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Models\Master\Common\Branch;
use App\Support\Journal\FinancialReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialReportController extends Controller
{

    private FinancialReportService $financialReportService;
    private Branch $branch;

    public function __construct()
    {
        $this->financialReportService = new FinancialReportService();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.journals.financial-report.index');
    }


    public function data(Request $request): JsonResponse
    {
        return response()->json($this->financialReportService->data($request));
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json([
            'fixed_asset' => $this->financialReportService->filter($request)['fixed_asset'],
            'total_depreciation_asset' => $this->financialReportService->filter($request)['total_depreciation_asset'],
            'total_fixed_asset' => $this->financialReportService->filter($request)['total_fixed_asset'],
        ]);
    }

}
