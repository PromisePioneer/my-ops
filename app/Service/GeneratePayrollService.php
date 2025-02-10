<?php

namespace App\Service;

use AllowDynamicProperties;
use App\Models\User;
use App\Service\Attendances\AttendancesSummaryService;
use App\Service\HelperService\FinancialClosePeriodService;
use Illuminate\Database\Eloquent\Collection;

#[AllowDynamicProperties] class GeneratePayrollService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->attendanceSummaryService = new AttendancesSummaryService();
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }


    public function getUserJobInformation()
    {
        $user = User::with('jobInformation', 'roles', 'branch', 'company', 'overtimeAllowance', 'mealAllowance', 'transportationAllowance')->get();
        return self::userJobInformationFormattedData($user);
    }


    public function userJobInformationFormattedData(Collection $data): \Illuminate\Support\Collection
    {
        $startDate = $this->financialClosePeriodService->startDate()->format('Y-m-d');
        $endDate = $this->financialClosePeriodService->endDate()->format('Y-m-d');


        $user = $data->map(function ($user) use ($startDate, $endDate) {
            $overtimeAllowance = $this->overtimeAllowance($user, $startDate, $endDate);
            $mealAllowance = $this->mealAllowance($user, $startDate, $endDate);
            $positionAllowance = $user->jobInformation?->position_allowance;
            $transportationAllowance = $this->transportationAllowance($user, $startDate, $endDate);
            $totalAllowance = $overtimeAllowance + $mealAllowance + $positionAllowance + $transportationAllowance;

            return [
                'id' => $user->id,
                'name' => "({$user->nip}) {$user->name}",
                'roles' => $user->roles[0]?->name ?? 'N/A',
                'branch' => $user->branch?->name,
                'fixed_salary' => 'Rp.' . number_format((float)$user->jobInformation?->fixed_salary),
                'emp_status' => $user->jobInformation?->contract_status ?? 'N/A',
                'company' => $user->company->name,
                'overtime_allowance' => 'Rp.' . number_format($overtimeAllowance),
                'meal_allowance' => 'Rp.' . number_format($mealAllowance),
                'position_allowance' => 'Rp.' . number_format((float)$positionAllowance),
                'transportation_allowance' => 'Rp.' . number_format($transportationAllowance),
                'total_allowance' => 'Rp.' . number_format($totalAllowance)
            ];
        });

//        $data->setCollection($user);
        return $user;
    }


    public function overtimeAllowance(User $user, $startDate, $endDate): int|float
    {
        return $user->overtimeAllowance
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
    }


    public function mealAllowance(User $user, $startDate, $endDate): int|float
    {
        return $user->mealAllowance
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
    }


    public function transportationAllowance(User $user, $startDate, $endDate): int|float
    {
        return $user->transportationAllowance
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
    }
}
