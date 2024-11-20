<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

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
        $user = User::orderBy('nip')->paginate(self::$perPage);
        return self::formattedData($user);
    }


    public function formattedData($userData)
    {
        $data = $userData->getCollection()->map(function ($item) {

            $startDate = $this->financialClosePeriodService->startDate();
            $endDate = $this->financialClosePeriodService->endDate();

            $period = CarbonPeriod::create($startDate, $endDate);

            $employeeSchedules = EmployeeSchedule::with('workTime')
                ->where('employee_id', $item->absent_id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->keyBy('date');


            $dates = [];

            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $dates[$formattedDate] = collect([
                    'periodDate' => $formattedDate,
                    'employeeSchedules' => $employeeSchedules->get($formattedDate),
                ]);
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'absent_id' => $item->absent_id,
                'date' => collect($dates)->map(function ($date) {
                    return [
                        'period_date' => $date['periodDate'],
                        'schedules_date' => $date['employeeSchedules'],
                        'work_time_schedules' => $date['employeeSchedules']?->workTime?->name . ' (' . $date['employeeSchedules']?->workTime?->clock_in . ' - ' . $date['employeeSchedules']?->workTime?->clock_out . ') ',
                    ];
                })->values()->toArray(),
            ];
        });

        $userData->setCollection($data);
        return $userData;
    }
}
