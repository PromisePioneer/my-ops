<?php

namespace App\Service;

use App\Models\Attendances;
use App\Models\LeaveAndPermission;
use App\Models\NationalHoliday;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class AttendancesSummaryService
{

    private static int $perPage = 10;
    private Attendances $attendances;

    public function __construct()
    {
        $this->attendances = new Attendances();
    }

    public function attendancesPeriod(): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        return new LengthAwarePaginator(
            $this->attendances->attendancesPeriod()->forPage($currentPage, self::$perPage)->get(),
            $this->attendances->attendancesPeriod()->count(),
            self::$perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }


    public function attendancesDataInAMonth($month, $year): LengthAwarePaginator
    {
        $query = $this->attendances->getAttendancesSummaryInAMonth($month, $year);
        return $this->attendancesDataInAMonthPaginatedData($query, $month, $year);
    }


    public function attendancesDataInAMonthPaginatedData($attendances, $month, $year): LengthAwarePaginator
    {
        $formattedData = $this->attendancesDataInAMonthFormattedData($attendances, $month, $year);
        $paginator = new LengthAwarePaginator(
            $formattedData->forPage(Paginator::resolveCurrentPage(), self::$perPage),
            $formattedData->count(),
            self::$perPage,
        );

        $paginator->withPath(url("adms/attendances-summary/detail/data/01-{$month}-{$year}"));

        return $paginator;
    }


    public function attendancesDataInAMonthFormattedData($attendances, $month, $year): Collection
    {
        return $attendances->map(function ($items) use ($month, $year) {
            $dailyAttendances = $this->dailyAttendancesGroupBy($items);
            $totalMinutesLate = $this->calculateMinutesLate($dailyAttendances, $month, $year);
            $totalPresent = $items->where('status1', 0)->count();
            $calculateCuti = $this->calculateCuti($items, $month, $year);
            $calculateIzin = $this->calculateIzin($items, $month, $year);
            $calculateSakit = $this->calculatesakit($items, $month, $year);
            $notCheckout = $items->whereNull('status1', 0);
            $notCheckIn = $items->whereNull('status1', 1)->count();
            $totalRegularHolidayIn1Week = 4;
            $daysInMonth = Carbon::now()->daysInMonth;
            $checkHolidayInThisMonth = NationalHoliday::whereMonth('date', $month)->whereYear('date', $year)->count();
            $totalHoliday = $totalRegularHolidayIn1Week + $checkHolidayInThisMonth + $calculateIzin + $calculateCuti + $calculateSakit;
            $totalAbsent = $daysInMonth - $totalPresent - $totalHoliday;

            return [
                'branch' => $items->first()->branches_name,
                'employee_id' => $items->first()->employee_id,
                'nip' => $items->first()->user_nip,
                'name' => $items->first()->user_name,
                'total_hadir' => $totalPresent,
                'total_menit_terlambat' => (int) $totalMinutesLate,
                'cuti' => (int) $calculateCuti,
                'izin' => (int) $calculateIzin,
                'sakit' => (int) $calculateSakit,
                'total_absent' => (int) $totalAbsent,
                'not_checkin' => $notCheckIn,
                'not_checkout' => $notCheckout,
            ];
        })->values();
    }

    public function dailyAttendancesGroupBy($items)
    {
        return $items->groupBy(function ($item) {
            return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
        });
    }

    public function calculateMinutesLate($dailyAttendances, $month, $year): float|int
    {
        foreach ($dailyAttendances as $day => $dailyItems) {
            $checkIn = $dailyItems->where('status1', 0)->first();
            $userWorktime = WorkTime::where('name', $checkIn->work_time ?? 'Default')->first();
            if ($checkIn) {
                $expectedCheckInTime = $userWorktime->clock_in;
                $expectedCheckIn = Carbon::parse($checkIn->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
                $actualCheckIn = Carbon::parse($checkIn->timestamp);

                return $this->isEmployeeLate($actualCheckIn, $expectedCheckIn);
            }
        }
        return 0;
    }

    public function isEmployeeLate($actualCheckIn, $expectedCheckIn): float|int
    {
        if ($actualCheckIn->greaterThan($expectedCheckIn)) {
            return Carbon::parse($expectedCheckIn)->diffInMinutes($actualCheckIn);
        }

        return 0;
    }

    public function calculateCuti($items, $month, $year): int|float
    {
        $getFirstCuti = LeaveAndPermission::join('users', 'users.id', '=',
            'leaves_and_permissions.user_id')
            ->join('attendances', 'users.absent_id', '=', 'attendances.employee_id')
            ->whereMonth('start_date', $month)->whereYear('start_date', $year)
            ->where('confirmation_status', 'Diterima')->where('users.absent_id',
                $items->first()->employee_id)->where('leaves_status',
                'Cuti')->orderBy('leaves_and_permissions.created_at',
                'asc')->first();


        $getLastCuti = LeaveAndPermission::join('users', 'users.id', '=',
            'leaves_and_permissions.user_id')
            ->join('attendances', 'users.absent_id', '=', 'attendances.employee_id')
            ->whereMonth('start_date', $month)->whereYear('start_date', $year)
            ->where('confirmation_status', 'Diterima')->where('users.absent_id',
                $items->first()->employee_id)->where('leaves_status',
                'Cuti')->latest('leaves_and_permissions.created_at')->first();


        return Carbon::parse($getFirstCuti?->start_date)->diffInDays(Carbon::parse($getLastCuti?->end_date));
    }

    public function calculateIzin($items, $month, $year): int|float
    {
        $getFirstIzin = LeaveAndPermission::join('users', 'users.id', '=',
            'leaves_and_permissions.user_id')
            ->join('attendances', 'users.absent_id', '=', 'attendances.employee_id')
            ->whereMonth('start_date', $month)->whereYear('start_date', $year)
            ->where('confirmation_status', 'Diterima')->where('users.absent_id',
                $items->first()->employee_id)->where('leaves_status',
                'Izin')->orderBy('leaves_and_permissions.created_at',
                'asc')->first();


        $getLastIzin = LeaveAndPermission::join('users', 'users.id', '=',
            'leaves_and_permissions.user_id')
            ->join('attendances', 'users.absent_id', '=', 'attendances.employee_id')
            ->whereMonth('start_date', $month)->whereYear('start_date', $year)
            ->where('confirmation_status', 'Diterima')->where('users.absent_id',
                $items->first()->employee_id)->where('leaves_status',
                'Izin')->latest('leaves_and_permissions.created_at')->first();


        return Carbon::parse($getFirstIzin?->start_date)->diffInDays(Carbon::parse($getLastIzin?->end_date));
    }

    public function calculatesakit($items, $month, $year): int|float
    {
        $getFirstSakit = LeaveAndPermission::join('users', 'users.id', '=',
            'leaves_and_permissions.user_id')
            ->join('attendances', 'users.absent_id', '=', 'attendances.employee_id')
            ->whereMonth('start_date', $month)->whereYear('start_date', $year)
            ->where('confirmation_status', 'Diterima')->where('users.absent_id',
                $items->first()->employee_id)->where('leaves_status',
                'Sakit')->orderBy('leaves_and_permissions.created_at',
                'asc')->first();


        $getLastSakit = LeaveAndPermission::join('users', 'users.id', '=',
            'leaves_and_permissions.user_id')
            ->join('attendances', 'users.absent_id', '=', 'attendances.employee_id')
            ->whereMonth('start_date', $month)->whereYear('start_date', $year)
            ->where('confirmation_status', 'Diterima')->where('users.absent_id',
                $items->first()->employee_id)->where('leaves_status',
                'Sakit')->latest('leaves_and_permissions.created_at')->first();


        return Carbon::parse($getFirstSakit?->start_date)->diffInDays(Carbon::parse($getLastSakit?->end_date));
    }

    public function searchAttendancesSummary(Request $request, $month, $year): LengthAwarePaginator
    {
        $search = $request->input('search');

        $startDate = Carbon::parse('01-'.$month.'-'.$year)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse('01-'.$month.'-'.$year)->endOfMonth()->format('Y-m-d');


        $query = $this->attendances->getAttendancesLog()->whereBetween('attendances.timestamp', [$startDate, $endDate]);

        if ($search) {
            $query->where('users.name', 'like', '%'.$search.'%')
                ->orWhere('branches.name', 'like', '%'.$search.'%')
                ->orWhere('work_time.name', 'like', '%'.$search.'%')
                ->orWhere('users.nip', 'like', '%'.$search.'%');
        }

        $attendances = $query->get();
        $groupedAttendances = $attendances->groupBy('user_name');

        return $this->attendancesDataInAMonthPaginatedData($groupedAttendances, $month, $year);
    }


    public function filterAttendancesSummaryByDate(
        string $startDate,
        string $endDate,
        string $month,
        string $year
    ): LengthAwarePaginator {
        $query = $this->attendances->getAttendancesLog()->whereBetween('attendances.timestamp', [$startDate, $endDate]);
        $attendances = $query->get();
        $groupedAttendances = $attendances->groupBy('user_name');
        return $this->attendancesDataInAMonthPaginatedData($groupedAttendances, $month, $year);
    }


    public function attendanceSummaryDetailForOneMonthBasedOnUserId($month, $year, $employeeId)
    {
        $query = $this->attendances->getAttendancesLog()
            ->where('attendances.employee_id', $employeeId)
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->orderBy('attendances.timestamp', 'ASC');


        $paginator = $query->paginate(self::$perPage);

        $groupedData = $paginator->getCollection()->groupBy(function ($item) {
            return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
        });

        $formattedData = $this->attendancesDataInAMonthFormattedData($groupedData);

        $paginator->setCollection($formattedData);

        return $paginator;
    }
}