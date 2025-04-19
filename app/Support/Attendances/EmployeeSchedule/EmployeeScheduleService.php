<?php

namespace App\Support\Attendances\EmployeeSchedule;

use AllowDynamicProperties;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Support\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

#[AllowDynamicProperties] class EmployeeScheduleService
{
    private static int $perPage = 50;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->startDate = $this->financialClosePeriodService->startDate();
        $this->endDate = $this->financialClosePeriodService->endDate();
    }


    public function query(): Builder
    {
        return User::with([
            'employeeSchedules' => function ($query) {
                $query->select('id', 'employee_id', 'start_date', 'work_time_id', 'status');
            },
            'roles:id,name',
            'userHasArea:id,user_id,area_id'
        ])
            ->orderBy('name');
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
        return self::formattedData($data);
    }

    public function filterByDate($request)
    {
        $startDate = Carbon::make($request->start_date);
        $endDate = Carbon::make($request->end_date);
        $user = EmployeeScheduleACLFilter::apply($this->query(), $request);
        $data = $user->paginate(self::$perPage);
        return self::formattedData($data, $startDate, $endDate);
    }


    public function formattedData($userData, $startDate = null, $endDate = null)
    {


        $startDate = $startDate ?? $this->startDate->format('Y-m-d');
        $endDate = $endDate ?? $this->endDate->format('Y-m-d');
        $period = collect(CarbonPeriod::create($startDate, $endDate))->map(fn($d) => $d->format('Y-m-d'));

        $userIds = $userData->pluck('id')->toArray();

        $allLeaves = $this->getLeaves($userIds);
        $allSick = $this->getSick($userIds);
        $allPermission = $this->getPermission($userIds);
        $importantLeaves = $this->getImportantLeaves($userIds);

        $data = $userData->getCollection()->map(function ($item) use ($period, $allLeaves, $allSick, $allPermission) {
            $schedules = $item->employeeSchedules->keyBy('start_date');
            $leaves = $allLeaves[$item->id] ?? [];
            $sick = $allSick[$item->id] ?? [];
            $permission = $allPermission[$item->id] ?? [];
            $importantLeaves = $allImportantLeaves[$item->id] ?? [];

            $dates = array_fill_keys($period->toArray(), []);

            foreach ($dates as $date => &$entry) {
                $entry = [
                    'periodDate' => $date,
                    'employeeSchedules' => $schedules[$date] ?? null,
                    'leaves' => $leaves[$date] ?? null,
                    'sick' => $sick[$date] ?? null,
                    'permission' => $permission[$date] ?? null,
                    'importantLeaves' => $importantLeaves[$date] ?? null,
                ];
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'absent_id' => $item->absent_id,
                'date' => collect($dates)->map(function ($date) use ($item) {
                    $weekHoliday = WeekHoliday::where('user_id', $item->id)
                        ->where('day', Carbon::parse($date['periodDate'])->dayName)
                        ->first();
                    return [
                        'period_date' => $date['periodDate'],
                        'schedules_date' => $date['employeeSchedules'] ?? null,
                        'is_holiday' => $date['employeeSchedules'] ? null : $weekHoliday?->is_holiday,
                        'work_time_schedules' => $date['employeeSchedules']?->workTime?->name,
                        'sick' => $date['sick'] ?? null,
                        'permission' => $date['permission'] ?? null,
                        'leaves' => $date['leaves'] ?? null,
                        'important_leaves' => $date['importantLeaves'],
                    ];
                })->values(),
                'area' => $item->userHasArea?->area,
            ];
        });

        $userData->setCollection($data);
        return $userData;

    }


    private function getLeaves(array $userIds): array
    {
        return $this->getLeaveData($userIds, 'Cuti');
    }

    private function getImportantLeaves(array $userIds): array
    {
        return $this->getLeaveData($userIds, 'Cuti Penting');
    }

    private function getSick(array $userIds): array
    {
        return $this->getLeaveData($userIds, 'Sakit');
    }

    private function getPermission(array $userIds): array
    {
        return $this->getLeaveData($userIds, 'Izin');
    }

    private function getLeaveData(array $userIds, string $type): array
    {
        $leaves = LeaveAndPermission::whereIn('user_id', $userIds)
            ->where('leaves_status', $type)
            ->where('confirmation_status', 'Diterima')
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
            })
            ->get()
            ->groupBy('user_id');

        $result = [];

        foreach ($leaves as $userId => $entries) {
            $dates = [];
            foreach ($entries as $entry) {
                foreach (CarbonPeriod::create($entry->start_date, $entry->end_date) as $date) {
                    $formattedDate = $date->format('Y-m-d');
                    $dates[$formattedDate] = [
                        'leave_date' => $formattedDate,
                        'status' => $type,
                    ];
                }
            }
            $result[$userId] = $dates;
        }

        return $result;
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


//    public function getPermission($user): array
//    {
//        $permission = $this->leavesQuery($user, 'Izin');
//
//        $permissionPeriod = [];
//
//        foreach ($permission as $dates) {
//            $permissionPeriod = array_merge(
//                $permissionPeriod,
//                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
//            );
//        }
//
//        $permission = [];
//        foreach ($permissionPeriod as $date) {
//            $formattedDate = Carbon::parse($date)->format('Y-m-d');
//            $permission[$formattedDate] = collect([
//                'permission_date' => $formattedDate,
//                'status' => 'Izin',
//            ]);
//        }
//
//        return $permission;
//    }
//
//
//    public function getSick($user): array
//    {
//        $sick = $this->leavesQuery($user, 'Sakit');
//
//        $sickPeriod = [];
//
//        foreach ($sick as $dates) {
//            $sickPeriod = array_merge(
//                $sickPeriod,
//                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
//            );
//        }
//
//        $sick = [];
//        foreach ($sickPeriod as $date) {
//            $formattedDate = Carbon::parse($date)->format('Y-m-d');
//            $sick[$formattedDate] = collect([
//                'sick_date' => $formattedDate,
//                'status' => 'Sakit',
//            ]);
//        }
//
//        return $sick;
//    }
//
//    public function getLeaves($user): array
//    {
//        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
//            ->where('leaves_status', 'Cuti')
//            ->where('confirmation_status', 'Diterima')
//            ->where(function ($query) {
//                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
//                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
//            })->get();
//
//
//        $leavePeriods = [];
//
//        foreach ($leaveAndPermission as $dates) {
//            $leavePeriods = array_merge(
//                $leavePeriods,
//                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
//            );
//        }
//
//        $leaves = [];
//        foreach ($leavePeriods as $date) {
//            $formattedDate = Carbon::parse($date)->format('Y-m-d');
//            $leaves[$formattedDate] = collect([
//                'leaves_date' => $formattedDate,
//                'status' => 'Cuti',
//            ]);
//        }
//
//        return $leaves;
//    }

}
