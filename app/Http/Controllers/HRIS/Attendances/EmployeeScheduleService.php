<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class EmployeeScheduleService
{

    private static int $perPage = 10;
    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function data(Request $request)
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $user = User::with('roles');

        if ($request->user()->role('Super Admin')) {
            $data = $user->paginate(self::$perPage);
        }

        if ($request->user()->role('NOC Supervisor')) {
            $data = $user->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->whereNull('branch_id')->paginate(self::$perPage);
        }

        $data = $user->paginate(self::$perPage);

        return self::formattedData($data, $startDate, $endDate);
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $user = User::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);
        return self::formattedData($user, $startDate, $endDate);
    }

    public function filterByDate($startDate, $endDate)
    {
        $user = User::paginate(self::$perPage);
        return self::formattedData($user, $startDate, $endDate);
    }


    public function formattedData($userData, $startDate, $endDate)
    {

        $period = CarbonPeriod::create($startDate, $endDate);

        $data = $userData->getCollection()->map(function ($item) use ($startDate, $endDate, $period) {
            $allSchedules = EmployeeSchedule::with('workTime')
                ->whereBetween('date', [$startDate, $endDate])
                ->where('employee_id', $item->absent_id)
                ->get()
                ->keyBy('date');

            // Create dates data
            $dates = [];
            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $dates[$formattedDate] = [
                    'periodDate' => $formattedDate,
                    'employeeSchedules' => $allSchedules->get($formattedDate),
                ];
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'absent_id' => $item->absent_id,
                'date' => collect($dates)->map(function ($date) {
                    return [
                        'period_date' => $date['periodDate'],
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
