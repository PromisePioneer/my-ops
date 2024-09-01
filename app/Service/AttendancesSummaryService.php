<?php

namespace App\Service;

use App\Models\Attendances;
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
        $formattedData = $this->attendancesDataInAMonthFormattedData($attendances);
        $paginator = new LengthAwarePaginator(
            $formattedData->forPage(Paginator::resolveCurrentPage(), self::$perPage),
            $formattedData->count(),
            self::$perPage,
        );

        $paginator->withPath(url("adms/attendances-summary/detail/data/01-{$month}-{$year}"));

        return $paginator;
    }


    public function attendancesDataInAMonthFormattedData($attendances): Collection
    {
        return $attendances->map(function ($items) {
            $dailyAttendances = self::dailyAttendancesGroupBy($items);
            $totalMinutesLate = $this->calculateMinutesLate($dailyAttendances);
            $totalPresent = $items->where('status1', 0)->count();

            return [
                'branch' => $items->first()->branches_name,
                'employee_id' => $items->first()->employee_id,
                'nip' => $items->first()->user_nip,
                'name' => $items->first()->user_name,
                'total_hadir' => $totalPresent,
                'total_menit_terlambat' => (int) $totalMinutesLate,
            ];
        })->values();
    }


    private static function dailyAttendancesGroupBy($items)
    {
        return $items->groupBy(function ($item) {
            return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
        });
    }


    public function calculateMinutesLate($dailyAttendances): float|int
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