<?php

namespace App\Service;

use App\Models\Attendances;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use function App\Helper\formatDate;

class AttendancesService
{
    private static int $perPage = 10;
    private Attendances $attendances;

    public function __construct()
    {
        $this->attendances = new Attendances();
    }

    public function attendancesLog()
    {
        $query = $this->attendances->getAttendancesLog()->whereMonth('attendances.timestamp',
            Carbon::now())->get();

        $groupedData = $query->groupBy(function ($item) {
            return $item->user_name;
        });

        $formattedData = $this->formatAttendanceLog($groupedData);

        $paginator = new LengthAwarePaginator(
            $formattedData->forPage(Paginator::resolveCurrentPage(), self::$perPage),
            $formattedData->count(),
            self::$perPage,
        );

        $paginator->withPath(url("adms/attendances/data"));

        return $paginator;
    }


    private function formatAttendanceLog($attendancesLog)
    {
        return $attendancesLog->map(function ($items) {
            $checkIn = $items->where('status1', 0)->first();
            $checkOut = $items->where('status1', 1)->last();

            if (!$checkIn) {
                return null;
            }

            $userWorkTime = WorkTime::where('name', $checkIn->work_time ?? 'Default')->first();
            $expectedCheckIn = self::concatenateExpectedCheckInTime($items, $userWorkTime);
            $actualCheckIn = Carbon::parse($checkIn?->timestamp);
            $calculateMinutesLate = self::calculateMinutesLate($actualCheckIn, $expectedCheckIn);

            return [
                'name' => $items->first()->user_name,
                'work_time' => $userWorkTime->name,
                'date' => formatDate($items->first()->timestamp),
                'employee_id' => $checkIn->employee_id,
                'checkin_time' => $checkIn ? Carbon::parse($checkIn?->timestamp)->format('H:i') : null,
                'late_checkin' => (int) $calculateMinutesLate,
                'checkout_time' => $checkOut ? Carbon::parse($checkOut->timestamp)->format('H:i') : null,
            ];
        })->filter()->values();
    }

    public static function concatenateExpectedCheckInTime($items, $userWorkTime): string
    {
        $expectedCheckInTime = $userWorkTime->clock_in;
        return Carbon::parse($items->first()->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
    }

    private static function calculateMinutesLate($actualCheckIn, $expectedCheckIn): ?float
    {
        if (!$actualCheckIn) {
            return null;
        }

        if ($actualCheckIn->greaterThan($expectedCheckIn)) {
            return Carbon::parse($expectedCheckIn)->diffInMinutes($actualCheckIn);
        }

        return 0;
    }
}
    