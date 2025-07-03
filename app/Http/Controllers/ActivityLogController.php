<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Support\HelperService\FinancialClosePeriodService;
use App\Support\Utility\ActivityLog\Service\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

#[AllowDynamicProperties] class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->activityLogService = new ActivityLogService();
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->startDate = $this->financialClosePeriodService->startDate();
        $this->endDate = $this->financialClosePeriodService->endDate();
    }


    public function index(): View
    {
        $this->authorize('view', Activity::class);
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        return view('pages.utilities.activity-log.index', compact('startDate', 'endDate'));
    }


    public function data(): JsonResponse
    {
        return response()->json($this->activityLogService->data());
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->activityLogService->filter($request));
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->activityLogService->search($request));
    }

}
