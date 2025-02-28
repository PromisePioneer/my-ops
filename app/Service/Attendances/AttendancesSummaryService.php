<?php

namespace App\Service\Attendances;

use AllowDynamicProperties;
use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AttendancesSummaryService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->startDate = $this->financialClosePeriodService->startDate();
        $this->endDate = $this->financialClosePeriodService->endDate();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $query = User::with([
            'attendancesSummary' => function ($query) {
                $query->whereBetween('date', [$this->startDate, $this->endDate]);
            }, 'roles'
        ])->where('active', 1);

        $data = AttendancesACLFilter::apply($query, $request);
        $attendanceSummary = $data->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($attendanceSummary, $this->startDate, $this->endDate);
    }


    public function getPeriod($startDate, $endDate, $user): array
    {
        $period = CarbonPeriod::create($startDate, $endDate);

        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $user->absent_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');


        $employeeSchedule = EmployeeSchedule::where('employee_id', $user->absent_id)
            ->whereBetween('start_date', [$startDate, $endDate])->orderBy('start_date', 'asc')->get()->keyBy('start_date');

        $dates = [];
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
                'employeeSchedule' => $employeeSchedule->get($formattedDate),
            ]);
        }

        return $dates;
    }


    public function formattedData(LengthAwarePaginator $user, $startDate, $endDate): LengthAwarePaginator
    {
        $data = $user->getCollection()->map(function ($user) use ($startDate, $endDate) {
            $totalMinutesLate = 0;
            $totalNotCheckIn = 0;
            $totalNotCheckOut = 0;
            $getPeriod = $this->getPeriod($startDate, $endDate, $user);
            $totalPresent = $user->attendancesSummary->count();
            $totalSick = $this->getSick($user, $startDate, $endDate);
            $totalLeaves = $this->getLeaves($user, $startDate, $endDate);
            $totalPermission = $this->getPermission($user, $startDate, $endDate);
            $totalAbsent = 0;


            foreach ($getPeriod as $period) {
                if($period['employeeSchedule']?->status === 'L'){
                    continue;
                }

                if (empty($period['attendanceData']) && Carbon::parse($period['attendancesDate'])->lessThan(Carbon::now())) {
                    $totalAbsent++;
                }

            }

            foreach ($user->attendancesSummary as $attendance) {
                if (empty($attendance->clock_in) && $attendance->clock_out) {
                    $totalNotCheckIn++;
                }

                if ($attendance->date != Carbon::now()->format('Y-m-d')) {
                    if (empty($attendance->clock_out) && $attendance->clock_in) {
                        $totalNotCheckOut++;
                    }
                }

                $userWorktime = WorkTime::where('id', $attendance->work_time_id)->first();
                if ($this->calculateLate($userWorktime, $attendance) > 2.5) {
                $totalMinutesLate += $this->calculateLate($userWorktime, $attendance);
                }
            }

            if ($totalPermission > 0) {
                $totalAbsent -= $totalPermission;
            }

            if ($totalLeaves > 0) {
                $totalAbsent -= $totalLeaves;
            }

            if ($totalSick > 0) {
                $totalAbsent -= $totalSick;
            }


            return [
                'id' => $user->id,
                'user_nip' => $user->nip,
                'user_name' => $user?->name,
                'role' => $user->roles[0]?->name ?? '',
                'total_minutes_late' => (int)$totalMinutesLate,
                'total_not_check_in' => $totalNotCheckIn,
                'total_not_check_out' => $totalNotCheckOut,
                'total_present' => $totalPresent,
                'total_leaves' => $totalLeaves,
                'total_sick' => $totalSick,
                'total_permission' => $totalPermission,
                'total_absent' => $totalAbsent
            ];
        });


        $user->setCollection($data);
        return $user;
    }

    public function leavesQuery($user, $startDate, $endDate, $leaveStatus)
    {
        return LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', $leaveStatus)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            });
    }

    public function getLeaves($user, $startDate, $endDate): int
    {
        $leaveStatus = 'Cuti';
        $leaveAndPermission = $this->leavesQuery($user, $startDate, $endDate, $leaveStatus)->get();

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

        return count($leaves);
    }

    public function getSick($user, $startDate, $endDate): int
    {

        $leaveStatus = 'Sakit';
        $leaveAndPermission = $this->leavesQuery($user, $startDate, $endDate, $leaveStatus)->get();

        $leavePeriods = [];

        foreach ($leaveAndPermission as $dates) {
            $leavePeriods = array_merge(
                $leavePeriods,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $sick = [];
        foreach ($leavePeriods as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $sick[$formattedDate] = collect([
                'leaves_date' => $formattedDate,
                'status' => $leaveStatus,
            ]);
        }
        return count($sick);
    }


    public function getPermission($user, $startDate, $endDate): int
    {
        $leaveStatus = 'Izin';
        $leaveAndPermission = $this->leavesQuery($user, $startDate, $endDate, $leaveStatus)->get();

        $permissionPeriods = [];

        foreach ($leaveAndPermission as $dates) {
            $permissionPeriods = array_merge(
                $permissionPeriods,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $permissions = [];
        foreach ($permissionPeriods as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $permissions[$formattedDate] = collect([
                'leaves_date' => $formattedDate,
                'status' => $leaveStatus,
            ]);
        }
        return count($permissions);
    }



    public function calculateLate($userWorktime, $attendance): float|int
    {
        $totalMinutesLate = 0;
        $actualCheckIn = Carbon::make($attendance?->clock_in ?? $attendance->date);
        $workDate = $attendance?->date;
        $expectedCheckIn = Carbon::parse("$workDate {$userWorktime?->clock_in}");

        $newExpectedCheckIn = null;
        if ($userWorktime?->name === "Malam") {
            $newExpectedCheckIn = $expectedCheckIn->copy()->addDays();
        }

        $checkInToUse = $newExpectedCheckIn ?? $expectedCheckIn;


        if ($checkInToUse->diffInMinutes($actualCheckIn) > 2.5) {
            $lateness = $checkInToUse->diffInMinutes($actualCheckIn);
            $totalMinutesLate += $lateness;
        }

        return $totalMinutesLate;
    }


    public function filter($request): LengthAwarePaginator
    {
        $startDate = $request->start_date ?? $this->startDate;
        $endDate = $request->end_date ?? $this->endDate;


        $query = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate, $request) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        ], 'branch')->where('active', 1);


        $filter = AttendanceQueryFilter::apply($query, $request);
        $aclFilter = AttendancesACLFilter::apply($filter, $request);

        $attendanceSummary = $aclFilter->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($attendanceSummary, $startDate, $endDate);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $startDate = isset($request->start_date)
            ? Carbon::parse($request->start_date)
            : $this->financialClosePeriodService->startDate();
        $endDate = isset($request->end_date)
            ? Carbon::parse($request->end_date)
            : $this->financialClosePeriodService->endDate();
        $search = $request->input('search');


        $query = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate, $request) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        ], 'branch')->where('active', 1);

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('email', 'like', '%' . $search . '%');
        }

        $user = $query->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($user, $startDate, $endDate);
    }
}
