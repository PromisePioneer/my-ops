<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public function getAttendancesDataBasedOnUserId(int $perPage, int $absentId): LengthAwarePaginator
    {
        $query = self::join(
            'users',
            'users.absent_id',
            '=',
            'attendances.employee_id'
        )->where('attendances.employee_id', $absentId)->paginate($perPage);

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

    public function attendancesPeriod()
    {
        return self::select(
            DB::raw("CONCAT(MONTH(timestamp), '-', YEAR(timestamp)) as waktu"),
            DB::raw('YEAR(timestamp) as tahun'),
            DB::raw('MONTHNAME(timestamp) as bulan')
        )
            ->orderBy('tahun', 'desc')
            ->distinct();
    }

    public function getAttendancesSummaryInAMonth(string $month, string $year): Collection
    {
        return $this->getAttendancesLog()->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)->get()->groupBy('user_name');
    }

    public function getAttendancesLog()
    {
        return self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->whereNotNull('attendances.employee_id')
            ->whereNotNull('users.name')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->orderBy('attendances.timestamp', 'desc')
            ->select(
                'attendances.employee_id',
                'users.name as user_name',
                'users.nip as user_nip',
                'attendances.timestamp',
                'attendances.status1',
                'work_time.name as work_time',
                'attendances.sn'
            );
    }

    public function formatGroupedDataForAttendancesSummary($attendances)
    {
        return $attendances->map(function ($items) {
            $totalMinutesLate = 0;

            // Group by each day to calculate daily lateness
            $dailyAttendances = $items->groupBy(function ($item) {
                return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
            });

            foreach ($dailyAttendances as $day => $dailyItems) {
                $checkIn = $dailyItems->where('status1', 0)->first();
                if ($checkIn) {
                    $userWorktime = WorkTime::where('name', $checkIn->work_time ?? null)->first();
                    $defaultWorkTime = WorkTime::where('id', 1)->first();
                    $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime->clock_in;

                    // Combine the date of check-in with the expected time
                    $expectedCheckIn = Carbon::parse($checkIn->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
                    $expectedCheckIn = Carbon::parse($expectedCheckIn);

                    // Calculate lateness in minutes for that day
                    $actualCheckIn = Carbon::parse($checkIn->timestamp);
                    if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                        $minutesLate = $expectedCheckIn->diffInMinutes($actualCheckIn);
                        $totalMinutesLate += $minutesLate;
                    }
                }
            }

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

    public function attendanceSummaryDetailForOneMonthBasedOnUserId(
        string $month,
        string $year,
        int $employeeId,
        int $perPage
    ): LengthAwarePaginator {
        $query = self::select(
            'attendances.employee_id',
            'users.name as user_name',
            'attendances.timestamp',
            'attendances.status1',
            'work_time.name as work_time'
        )
            ->join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->where('attendances.employee_id', $employeeId)
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->orderBy('attendances.timestamp', 'ASC');

        $paginator = $query->paginate($perPage);

        $groupedData = $paginator->getCollection()->groupBy(function ($item) {
            return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
        });

        $formattedData = $this->formatGroupedData($groupedData);
        $paginator->setCollection($formattedData);
        return $paginator;
    }

    private function formatGroupedData($groupedData)
    {
        return $groupedData->map(function ($items) {
            $checkIn = $items->where('status1', 0)->first();
            $checkOut = $items->where('status1', 1)->first();

            if ($checkIn) {
                $userWorktime = WorkTime::where('name', $checkIn->work_time)->first();
                $defaultWorkTime = WorkTime::where('id', 1)->first();
                $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime?->clock_in;

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

            return null;
        })->filter()->values();
    }
}
