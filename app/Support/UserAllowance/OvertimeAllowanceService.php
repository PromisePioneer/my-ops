<?php

namespace App\Support\UserAllowance;

use AllowDynamicProperties;
use App\Http\Requests\Allowances\UserHasOvertimeRequest;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\UserHasOvertime;
use App\Support\HelperService\FinancialClosePeriodService;
use App\Support\HelperService\HandleFileUploadService;
use Carbon\Carbon;

#[AllowDynamicProperties] class OvertimeAllowanceService
{

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->handleFileUploadService = new HandleFileUploadService();
    }



    public function store(UserHasOvertimeRequest $request)
    {
        $user = $this->getUserFixedSalary($request);
        $getPeriodOfWork = $this->getPeriodOfWork();

        $data = $request->validated();
        $data['date'] = Carbon::now();
//        $data['amount'] = ($user->jobInformation->fixed_salary / $getPeriodOfWork / 8) * $request->hours;
        $data['amount'] = '123213213';
        $data['file'] = $this->handleFileUploadService->upload($request, 'documents/overtime', 'file');
        return UserHasOvertime::create($data);
    }

    public function getUserFixedSalary(UserHasOvertimeRequest $request)
    {
        return User::with('jobInformation')->where('id', $request->user_id)->first();
    }

    private function getPeriodOfWork()
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();


        $nationalHoliday = NationalHoliday::whereMonth('date', $startDate)->count();
        $weekEndCount = $endDate->diffInWeeks($endDate);
        return $startDate->diffInDays($endDate) - $weekEndCount - $nationalHoliday;
    }

    public function update(UserHasOvertimeRequest $request, UserHasOvertime $userHasOvertime): bool
    {
        $getPeriodOfWork = $this->getPeriodOfWork();
        $user = $this->getUserFixedSalary($request);

        $data = $request->validated();
        $data['date'] = Carbon::now();
        $data['amount'] = ($user->jobInformation->fixed_salary / $getPeriodOfWork / 8) * $request->hours;
        return $userHasOvertime->update($data);
    }
}
