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

        $user = User::with('roles', 'userHasArea');


        if ($request->user()->hasRole('NOC Supervisor')) {
            $user->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->whereNull('branch_id')->paginate(self::$perPage);
        }

        if ($request->user()->hasRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner')) {
            $user->paginate(self::$perPage);
        }


        if ($request->user()->hasRole('Head Engineer', 'Senior Engineer')) {
            $user->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where('branch_id', $request->user()->branch_id)->paginate(self::$perPage);
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1]);
            })
                ->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff']);
            })->whereNull('branch_id')->paginate(self::$perPage);
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $user->where('branch_id', $request->user()->branch_id)->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('KU Head Engineer')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasAnyRole('Quality Controller Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
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
                'area' => $item->userHasArea?->area,
            ];
        });

        $userData->setCollection($data);
        return $userData;
    }

}
