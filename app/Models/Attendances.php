<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Attendances extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    protected $fillable = [
        'sn',
        'table',
        'stamp',
        'employee_id',
        'timestamp',
        'status1',
    ];

    public function getAttendanceWithPagination(int $perPage): LengthAwarePaginator
    {
        $query = self::select('attendances.employee_id', 'users.name as user_name', 'attendances.timestamp',
            'attendances.status1', 'work_time.name as work_time')
            ->leftjoin('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->orderBy('attendances.timestamp', 'DESC');

        $paginator = $query->paginate($perPage);

        // Lakukan grouping setelah data diambil untuk page tertentu
        $groupedData = $paginator->getCollection()->groupBy(function ($item) {
            return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
        });

        $formattedData = $this->formatGroupedData($groupedData);

        $paginator->setCollection($formattedData);

        return $paginator->withPath(url('/adms/attendances/data'));
    }

    private function formatGroupedData($groupedData)
    {
        return $groupedData->map(function ($items) {
            $checkIn = $items->where('status1', 0)->first();
            $checkOut = $items->where('status1', 1)->last();

            if ($checkIn) { // Pastikan ada data check-in
                $userWorktime = WorkTime::where('name', $checkIn->work_time)->first();
                $defaultWorkTime = WorkTime::where('id', 1)->first();
                $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime->clock_in;

                $expectedCheckIn = Carbon::parse($checkIn->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
                $expectedCheckIn = Carbon::parse($expectedCheckIn);

                $actualCheckIn = Carbon::parse($checkIn->timestamp);
                $minutesLate = $actualCheckIn->greaterThan($expectedCheckIn) ? $expectedCheckIn->diffInMinutes($actualCheckIn) : 0;

                return [
                    'name' => $checkIn->user_name,
                    'work_time' => $userWorktime ? $userWorktime->name : $defaultWorkTime->name,
                    'date' => Carbon::parse($checkIn->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y'),
                    'employee_id' => $checkIn->employee_id,
                    'checkin_time' => $actualCheckIn->format('H:i'),
                    'late_checkin' => (int) $minutesLate,
                    'checkout_time' => $checkOut ? Carbon::parse($checkOut->timestamp)->format('H:i') : null,
                ];
            }

            // Jika tidak ada data check-in, jangan tambahkan ke hasil
            return null;
        })->filter()->values();
    }


    public function getAttendancesDataBasedOnUserId(int $perPage, int $absentId): LengthAwarePaginator
    {
        $query = self::join('users', 'users.absent_id', '=',
            'attendances.employee_id')->where('attendances.employee_id', $absentId)->paginate($perPage);

        self::formattedAbsentDataBasedOnUserId($query);
        return $query;
    }


    private static function formattedAbsentDataBasedOnUserId(LengthAwarePaginator $attedancesData): void
    {
        $formattedAttendances = $attedancesData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'timestamp' => Carbon::parse($item->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y H:i:s'),
                'status1' => $item->status1,
            ];
        });

        $attedancesData->setCollection($formattedAttendances);
    }


    public function getAttendancesPeriod(int $perPage): LengthAwarePaginator
    {
        return self::selectRaw("CONCAT(MONTH(timestamp), '-', YEAR(timestamp)) as waktu")
            ->distinct()
            ->paginate($perPage);
    }


    public function getAttendancesBasedOnPeriod(string $month, string $year, int $perPage): LengthAwarePaginator
    {
        $attendances = self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->orderBy('attendances.timestamp', 'DESC')
            ->select('users.nip as user_nip', 'branches.name as branches_name', 'users.name as user_name',
                'attendances.timestamp',
                'attendances.status1',
                'work_time.name as work_time', 'attendances.employee_id')
            ->get()
            ->groupBy(function ($item) {
                return $item->user_name;
            });

        $formattedData = $this->formatGroupedDataForAttendancesSummary($attendances, $month, $year);
        $paginator = new LengthAwarePaginator($formattedData->forPage(Paginator::resolveCurrentPage(), $perPage),
            $formattedData->count(), $perPage);

        $paginator->withPath(url("adms/attendances-summary/detail/data/01-{$month}-{$year}"));

        return $paginator;
    }

    private function formatGroupedDataForAttendancesSummary($attendances, $month, $year)
    {
        return $attendances->map(function ($items) use ($month, $year) {
            $totalMinutesLate = 0;

            // Group by each day to calculate daily lateness
            $dailyAttendances = $items->groupBy(function ($item) {
                return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
            });

            foreach ($dailyAttendances as $day => $dailyItems) {
                $checkIn = $dailyItems->where('status1', 0)->first();
                $userWorktime = WorkTime::where('name', $checkIn?->work_time)->first();
                $defaultWorkTime = WorkTime::where('id', 1)->first();
                $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime->clock_in;

                // Combine the date of check-in with the expected time
                $expectedCheckIn = Carbon::parse($checkIn?->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
                $expectedCheckIn = Carbon::parse($expectedCheckIn);

                // Calculate lateness in minutes for that day
                $actualCheckIn = Carbon::parse($checkIn?->timestamp);
                if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                    $minutesLate = $expectedCheckIn->diffInMinutes($actualCheckIn);
                    $totalMinutesLate += $minutesLate;
                }
            }

            $totalPresent = $items->where('status1', 0)->count();

            return [
                'branch' => $items->first()->branch_name ?? 'Pusat',
                'employee_id' => $items->first()->employee_id,
                'nip' => $items->first()->user_nip,
                'name' => $items->first()->user_name,
                'total_hadir' => $totalPresent,
                'total_menit_terlambat' => (int) $totalMinutesLate
            ];
        })->values();
    }

    public function searchAttendancesSummary(Request $request)
    {
        $search = $request->input('search');

        $query = self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->select('users.nip as user_nip', 'users.name as user_name', 'attendances.timestamp', 'attendances.status1',
                'work_time.name as work_time', 'attendances.employee_id');

        if ($search) {
            $query->where('users.name', 'like', '%'.$search.'%')
                ->orWhere('branches.name', 'like', '%'.$search.'%')
                ->orWhere('work_time.name', 'like', '%'.$search.'%')
                ->orWhere('users.nip', 'like', '%'.$search.'%');
        }

        $attendances = $query->orderBy('attendances.timestamp', 'DESC')->get();

        $groupedAttendances = $attendances->groupBy(function ($item) {
            return $item->user_name;
        });

        return $this->formatGroupedDataForAttendancesSummary($groupedAttendances, null, null);
    }


    public function filterAttendancesSummaryByDate(
        $startDate,
        $endDate,
        $month,
        $year,
        int $perPage
    ): LengthAwarePaginator {
        $attendances = self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->whereBetween('attendances.timestamp', [$startDate, $endDate])
            ->orderBy('attendances.timestamp', 'DESC')
            ->select('users.nip as user_nip', 'branches.name as branches_name', 'users.name as user_name',
                'attendances.timestamp',
                'attendances.status1',
                'work_time.name as work_time', 'attendances.employee_id')
            ->get()
            ->groupBy(function ($item) {
                return $item->user_name;
            });

        $formattedData = $this->formatGroupedDataForAttendancesSummary($attendances, $month, $year);
        $paginator = new LengthAwarePaginator($formattedData->forPage(Paginator::resolveCurrentPage(), $perPage),
            $formattedData->count(), $perPage);

        $paginator->withPath(url("adms/attendances-summary/detail/data/01-{$month}-{$year}"));

        return $paginator;
    }


    public function attendanceSummaryDetailForOneMonthBasedOnUserId(
        string $month,
        string $year,
        int $employeeId,
        int $perPage
    ): LengthAwarePaginator {
        $query = self::select('attendances.employee_id', 'users.name as user_name', 'attendances.timestamp',
            'attendances.status1', 'work_time.name as work_time')
            ->leftJoin('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->where('attendances.employee_id', $employeeId);


        $paginator = $query->paginate($perPage);

        // Lakukan grouping setelah data diambil untuk page tertentu
        $groupedData = $paginator->getCollection()->groupBy(function ($item) {
            return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
        });

        $formattedData = $this->formatGroupedData($groupedData);

        $paginator->setCollection($formattedData);

        return $paginator;
    }

}
