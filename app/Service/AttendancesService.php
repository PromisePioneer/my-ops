<?php

namespace App\Service;

use App\Models\Attendances;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class AttendancesService
{
    private static int $perPage = 10;
    private Attendances $attendances;

    public function __construct()
    {
        $this->attendances = new Attendances();
    }

    public function attendancesLog(): LengthAwarePaginator
    {
        $paginatedResults = $this->attendances->getAttendancesLog()->paginate(self::$perPage);

        $groupedData = collect($paginatedResults->items())->groupBy(function ($item) {
            return $item->employee_id;
        });

        $formattedData = $this->formatAttendanceLog($groupedData);

        return new LengthAwarePaginator(
            $formattedData,
            $paginatedResults->total(),
            self::$perPage,
            $paginatedResults->currentPage(),
            ['path' => url('/adms/attendances/data')]
        );
    }


    private function formatAttendanceLog($attendancesLog)
    {
        return $attendancesLog->map(function ($items) {
            $checkIn = $items->where('status1', 0)->first();
            $checkOut = $items->where('status1', 1)->last();
            $userWorkTime = WorkTime::where('name', $checkIn->work_time ?? 'Default')->first();
            $expectedCheckIn = self::concatenateExpectedCheckInTime($items, $userWorkTime);
            $actualCheckIn = Carbon::parse($checkIn->timestamp);
            $calculateMinutesLate = self::calculateMinutesLate($actualCheckIn, $expectedCheckIn);

            return [
                'name' => $items->first()->user_name,
                'work_time' => $userWorkTime->name,
                'date' => formatDate($items->first()->timestamp),
                'employee_id' => $checkIn->employee_id ?? null,
                'checkin_time' => $actualCheckIn->format('H:i'),
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

    private static function calculateMinutesLate($actualCheckIn, $expectedCheckIn): float
    {
        if ($actualCheckIn->greaterThan($expectedCheckIn)) {
            return Carbon::parse($expectedCheckIn)->diffInMinutes($actualCheckIn);
        }

        return 0;
    }
}
    