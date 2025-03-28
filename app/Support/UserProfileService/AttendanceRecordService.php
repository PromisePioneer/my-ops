<?php

namespace App\Support\UserProfileService;

use AllowDynamicProperties;
use App\Http\Requests\AttendancesSummaryFilterByDateRequest;
use App\Support\Attendances\AttendanceSummaryDetailService;
use App\Support\HelperService\FinancialClosePeriodService;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AttendanceRecordService
{

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->startDate = $this->financialClosePeriodService->startDate();
        $this->endDate = $this->financialClosePeriodService->endDate();
        $this->attendanceSummaryDetailService = new AttendanceSummaryDetailService();
    }


    public function data(Request $request)
    {
        return $this->attendanceSummaryDetailService->data($request, $request->user()->absent_id);
    }


    public function filter(AttendancesSummaryFilterByDateRequest $request)
    {
        return $this->attendanceSummaryDetailService->filterByDate($request, $request->user());
    }

}
