<?php

namespace App\Service\Attendances;

use App\Models\Attendances;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceSummaryDetailService
{

    private AttendancesSummaryService $attendaceSummaryService;

    public function __construct()
    {
        $this->attendanceSummaryService = new AttendancesSummaryService();
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public static function concatenateExpectedCheckInTime($attendance, $userWorkTime): string
    {
        $expectedCheckInTime = $userWorkTime->clock_in;
        return Carbon::parse($attendance->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
    }

    public function data(Request $request, $userId): LengthAwarePaginator
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : $this->financialClosePeriodService->startDate();
        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : $this->financialClosePeriodService->endDate();

        $attendanceQuery = Attendances::where('employee_id', $userId)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp');

        $total = $attendanceQuery->count();

        $perPage = 20;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;

        $attendances = $attendanceQuery->offset($offset)->limit($perPage)->get();

        $formattedData = $this->formatAttendanceData($attendances);

        return new LengthAwarePaginator(
            $formattedData,
            $total,
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    private function formatAttendanceData($attendances)
    {
        $groupedAttendances = $attendances->groupBy(function ($attendance) {
            return Carbon::parse($attendance->timestamp)->toDateString();
        });

        return $groupedAttendances->map(function ($dayAttendances, $date) {
            $checkIn = $dayAttendances->firstWhere('status1', 0);
            $checkOut = $dayAttendances->firstWhere('status1', 1);

            $period = CarbonPeriod::create(
                $this->financialClosePeriodService->startDate(),
                $this->financialClosePeriodService->endDate()
            );


            return [
                'date' => Carbon::parse($checkIn?->timestamp)->format('d/m/y')  ?? Carbon::parse($checkOut?->timestamp)->format('d/m/y'),
                'check_in_timestamp' => $checkIn ? Carbon::parse($checkIn?->timestamp)->format('H:i') : null,
                'check_out_timestamp' => $checkOut ? Carbon::parse($checkOut?->timestamp)->format('H:i') : null,
            ];
        })->values();
    }


}
