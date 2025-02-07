<?php

namespace App\Service\Attendances;

use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendancesSummaryService
{

    private FinancialClosePeriodService $financialClosePeriodService;
    private static int $perPage = 10;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $data = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }, 'roles'
        ])->where('active', 1);

        if ($request->user()->hasAnyRole('NOC Supervisor', 'NOC Staff')) {
            $data->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->where(function ($query) {
                $query->whereNull('branch_id')->orWhere('branch_id', 1);
            });
        }

        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner', 'Legal & Corporate Commissioner')) {
            $data->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('Head Engineer', 'Senior Engineer')) {
            $data->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id)
                    ->where('active', 1);
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            $data->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);;
            });
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            $data->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])->where('active', 1);;
            });
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            $data->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            })->whereNull('branch_id');
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $data->where('branch_id', $request->user()->branch_id)->paginate(self::$perPage);
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            $data->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            $data->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }


        $attendanceSummary = $data->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($attendanceSummary, $startDate, $endDate);
    }


    public function getPeriod($startDate, $endDate, $user)
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
                if (Carbon::parse($period['attendancesDate'])->greaterThan(Carbon::now())) {
                    if (empty($period['attendanceData']) || $period['employeeSchedule']?->status !== 'L') {
                        $totalAbsent++;
                    }
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
                $totalMinutesLate += $this->calculateLate($userWorktime, $attendance);
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


    public function getHoliday($user, $startDate, $endDate)
    {
        $holiday = NationalHoliday::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })->get();


        dd($holiday);
    }


    public function getLeaves($user, $startDate, $endDate): int
    {

        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Cuti')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
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
                'status' => 'Sakit',
            ]);
        }

        return count($leaves);
    }

    public function getSick($user, $startDate, $endDate): int
    {

        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Sakit')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->get();

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
                'status' => 'Sakit',
            ]);
        }

        return count($sick);
    }


    public function getPermission($user, $startDate, $endDate): int
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Izin')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->get();

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
                'status' => 'Sakit',
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


        if ($checkInToUse->diffInMinutes($actualCheckIn) >= 2.5) {
            $lateness = $checkInToUse->diffInMinutes($actualCheckIn);
            $totalMinutesLate += $lateness;
        }

        return $totalMinutesLate;
    }


    public function filter($startDate, $endDate, $roleId, $branchId): LengthAwarePaginator
    {
        $startDate = $startDate ?? $this->financialClosePeriodService->startDate();
        $endDate = $endDate ?? $this->financialClosePeriodService->endDate();


        $data = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        ])->where('active', 1);


        if (!empty($branchId) && Auth::user()->can('Filter Data Riwayat Absensi Berdasarkan Cabang')) {
            $data->where(function ($query) use ($branchId) {
                $query->where('branch_id', $branchId ?? null);
            });
        }

        if(!empty($roleId)){
                $data->whereHas('roles', function ($query) use ($roleId) {
                    $query->where('id', $roleId);
                });
        }

        $attendanceSummary = $data->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($attendanceSummary, $startDate, $endDate);
    }


    public function getUserWorktime($user)
    {
        $attendancesSummary = AttendancesSummary::where('employee_id', $user->absent_id)->first();
        return WorkTime::where('id', $attendancesSummary?->work_time_id)->first() ?? WorkTime::where('name', 'Default')->first();
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


        $data = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            },
        ]);


        if (!empty($search)) {
            $data->where('name', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%');
        }

        $user = $data->paginate(10)->onEachSide(1);
        return self::formattedData($user, $startDate, $endDate);
    }
}
