<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\CarbonPeriod;

class EmployeeScheduleService
{

    private static int $perPage = 10;
    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function data()
    {
        $user = User::paginate(self::$perPage);
        return self::formattedData($user);
    }


    public function formattedData($userData)
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        // Fetch all schedules grouped by employee_id
        $allSchedules = EmployeeSchedule::with('workTime')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy('employee_id');

        $period = CarbonPeriod::create($startDate, $endDate);

        $data = $userData->getCollection()->map(function ($item) use ($period, $allSchedules) {
            $employeeSchedules = $allSchedules->get($item->id)?->keyBy('date') ?? collect();

            // Create dates data
            $dates = [];
            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $dates[$formattedDate] = [
                    'periodDate' => $formattedDate,
                    'employeeSchedules' => $employeeSchedules->get($formattedDate),
                ];
            }

            // Map the final structure
            return [
                'id' => $item->id,
                'name' => $item->name,
                'absent_id' => $item->absent_id,
                'date' => collect($dates)->map(function ($date) {
                    return [
                        'period_date' => $date['periodDate'], // Safely access periodDate
                        'schedules_date' => $date['employeeSchedules'],
                        'work_time_schedules' => $date['employeeSchedules']?->workTime?->name .
                            ' (' . $date['employeeSchedules']?->workTime?->clock_in .
                            ' - ' . $date['employeeSchedules']?->workTime?->clock_out . ')',
                    ];
                })->values()->toArray(),
            ];
        });

        $userData->setCollection($data);
        return $userData;
    }

}
