<?php

namespace App\Service\UserAllowance;

use App\Http\Requests\Allowances\UserHasOvertimeRequest;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\UserHasOvertime;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;

class OvertimeAllowanceService
{
    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }


    public function store(UserHasOvertimeRequest $request)
    {
        $user = $this->getUserFixedSalary($request);
        $getPeriodOfWork = $this->getPeriodOfWork();

        $data = $request->validated();
        $data['date'] = Carbon::now();
        $data['amount'] = ($user->jobInformation->fixed_salary / $getPeriodOfWork / 8) * $request->hours;
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