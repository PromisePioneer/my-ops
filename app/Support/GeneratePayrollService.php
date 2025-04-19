<?php

namespace App\Support;

use AllowDynamicProperties;
use App\Models\User;
use App\Support\Attendances\AttendancesSummaryService;
use App\Support\HelperService\FinancialClosePeriodService;
use Illuminate\Support\Collection;

#[AllowDynamicProperties] class GeneratePayrollService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->attendanceSummaryService = new AttendancesSummaryService();
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }


    public function getUserJobInformation(): Collection
    {
        $user = User::with('jobInformation', 'roles', 'branch', 'company', 'overtimeAllowance', 'mealAllowance', 'transportationAllowance', 'SLADeduction', 'ninePastFifteenDeduction')->whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['Vendor']);
        })->get();
        return self::userJobInformationFormattedData($user);
    }


    public function userJobInformationFormattedData(Collection $data): Collection
    {
        $startDate = $this->financialClosePeriodService->startDate()->format('Y-m-d');
        $endDate = $this->financialClosePeriodService->endDate()->format('Y-m-d');


        return $data->map(function ($user) use ($startDate, $endDate) {
            $overtimeAllowance = $this->overtimeAllowance($user, $startDate, $endDate);
            $mealAllowance = $this->mealAllowance($user, $startDate, $endDate);
            $positionAllowance = $user->jobInformation?->position_allowance;
            $transportationAllowance = $this->transportationAllowance($user, $startDate, $endDate);
            $totalAllowance = $overtimeAllowance + $mealAllowance + $positionAllowance + $transportationAllowance;
            $slaDeduction = $this->slaDeduction($user, $startDate, $endDate);
            $ninePastFifteenDeduction = $this->ninePastFifteenDeduction($user, $startDate, $endDate);
            $additionalDeduction = $this->additionalDeduction($user, $startDate, $endDate);
            $totalDeduction = $slaDeduction + $ninePastFifteenDeduction + $additionalDeduction;

            return [
                'id' => $user->id,
                'name' => "({$user->nip}) {$user->name}",
                'roles' => $user->roles[0]?->name ?? 'N/A',
                'branch' => $user->branch?->name,
                'fixed_salary' => 'Rp.' . number_format((float)$user->jobInformation?->fixed_salary),
                'emp_status' => $user->jobInformation?->contract_status ?? 'N/A',
                'company' => $user->company?->name,
                'overtime_allowance' => 'Rp.' . number_format($overtimeAllowance),
                'meal_allowance' => 'Rp.' . number_format($mealAllowance),
                'position_allowance' => 'Rp.' . number_format((float)$positionAllowance),
                'transportation_allowance' => 'Rp.' . number_format($transportationAllowance),
                'sla_deduction' => 'Rp.' . number_format($slaDeduction),
                'nine_past_fifteen_deduction' => 'Rp.' . number_format($ninePastFifteenDeduction),
                'additional_deduction' => 'Rp.' . number_format($additionalDeduction),
                'total_allowance' => 'Rp.' . number_format($totalAllowance),
                'total_deduction' => 'Rp.' . number_format($totalDeduction),
            ];
        });
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


    public function slaDeduction(User $user, $startDate, $endDate): int|float
    {
        return $user->SLADeduction->whereBetween('date', [$startDate, $endDate])->sum('total_deduction_amount');
    }

    public function ninePastFifteenDeduction(User $user, $startDate, $endDate)
    {
        return $user->ninePastFifteenDeduction->whereBetween('date', [$startDate, $endDate])->sum('total_deduction_amount');
    }


    public function additionalDeduction(User $user, $startDate, $endDate)
    {
        return $user->additionalDeduction->whereBetween('date', [$startDate, $endDate])->sum('amount');
    }
}
