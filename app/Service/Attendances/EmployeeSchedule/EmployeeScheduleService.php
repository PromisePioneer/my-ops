<?php

namespace App\Service\Attendances\EmployeeSchedule;

use AllowDynamicProperties;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

#[AllowDynamicProperties] class EmployeeScheduleService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->startDate = $this->financialClosePeriodService->startDate();
        $this->endDate = $this->financialClosePeriodService->endDate();
    }


    public function query(): Builder
    {
        return User::with('employeeSchedules', 'roles', 'userHasArea')->orderBy('name');
    }



    public function data(Request $request)
    {

        $user = EmployeeScheduleACLFilter::apply($this->query(), $request);
        $data = $user->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($data);
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : $this->startDate;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : $this->endDate;

        $user = EmployeeScheduleACLFilter::apply($this->query(), $request);

        if (!empty($search)) {
            $user->where('name', 'like', '%' . $search . '%');
        }

        $data = $user->paginate(self::$perPage);
        return self::formattedData($data, $startDate, $endDate);
    }

    public function filterByDate($request, $startDate, $endDate)
    {
        $user = EmployeeScheduleACLFilter::apply($this->query(), $request);
        $data = $user->paginate(self::$perPage);
        return self::formattedData($data, $startDate, $endDate);
    }


    public function formattedData($userData)
    {
        $period = CarbonPeriod::create($this->startDate, $this->endDate);
        $data = $userData->getCollection()->map(function ($item) use ($period) {
            $allSchedules = $item->employeeSchedules->whereBetween('start_date', [$this->startDate, $this->endDate])->keyBy('start_date');
            $getLeaves = $this->getLeaves($item);
            $getSick = $this->getSick($item);
            $getPermission = $this->getPermission($item);

            $dates = [];
            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $leaveDetails = $getLeaves[$formattedDate] ?? null;
                $sickDetails = $getSick[$formattedDate] ?? null;
                $permissionDetails = $getPermission[$formattedDate] ?? null;
                $dates[$formattedDate] = [
                    'periodDate' => $formattedDate,
                    'employeeSchedules' => $allSchedules->get($formattedDate),
                    'leaves' => $leaveDetails,
                    'sick' => $sickDetails,
                    'permission' => $permissionDetails,
                ];
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'absent_id' => $item->absent_id,
                'date' => collect($dates)->map(function ($date) use ($item) {
                    $weekHoliday = $date['employeeSchedules'];
                    if (empty($weekHoliday)) {
                        $weekHoliday = WeekHoliday::where('user_id', $item->id)->where('day', Carbon::parse($date['periodDate'])->dayName)->first();
                    }
                    return [
                        'period_date' => $date['periodDate'],
                        'schedules_date' => $weekHoliday,
                        'work_time_schedules' => $date['employeeSchedules']?->workTime?->name,
                        'sick' => $date['sick'] ?? null,
                        'permission' => $date['permission'] ?? null,
                        'leaves' => $date['leaves'] ?? null,
                    ];
                })->values(),
                'area' => $item->userHasArea?->area,
            ];
        });

        $userData->setCollection($data);
        return $userData;
    }


    public function leavesQuery($user, $type)
    {
        return LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', $type)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
            })->get();
    }


    public function paginate($items, $perPage = 3, $page = null, $options = []): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    public function getPermission($user): array
    {
        $permission = $this->leavesQuery($user, 'Izin');

        $permissionPeriod = [];

        foreach ($permission as $dates) {
            $permissionPeriod = array_merge(
                $permissionPeriod,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $permission = [];
        foreach ($permissionPeriod as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $permission[$formattedDate] = collect([
                'permission_date' => $formattedDate,
                'status' => 'Izin',
            ]);
        }

        return $permission;
    }


    public function getSick($user): array
    {
        $sick = $this->leavesQuery($user, 'Sakit');

        $sickPeriod = [];

        foreach ($sick as $dates) {
            $sickPeriod = array_merge(
                $sickPeriod,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $sick = [];
        foreach ($sickPeriod as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $sick[$formattedDate] = collect([
                'sick_date' => $formattedDate,
                'status' => 'Sakit',
            ]);
        }

        return $sick;
    }

    public function getLeaves($user): array
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Cuti')
            ->where('confirmation_status', 'Diterima')
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
            })->get();


        $leavePeriods = [];

        foreach ($leaveAndPermission as $dates) {
            $leavePeriods = array_merge(
                $leavePeriods,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $leaves = [];
        foreach ($leavePeriods as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $leaves[$formattedDate] = collect([
                'leaves_date' => $formattedDate,
                'status' => 'Cuti',
            ]);
        }

        return $leaves;
    }

}
