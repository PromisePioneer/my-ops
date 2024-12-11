<?php

namespace App\Service\Attendances;

use App\Models\AttendancesSummary;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

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

        $attendanceSummary = $data->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($attendanceSummary, $startDate, $endDate);
    }


    public function formattedData(LengthAwarePaginator $user, $startDate, $endDate): LengthAwarePaginator
    {
        $data = $user->getCollection()->map(function ($user) use ($startDate, $endDate) {
            $nationalHoliday = NationalHoliday::whereBetween('date', [$startDate, $endDate])->count();
            $totalMinutesLate = 0;
            $periodOfWork = $startDate->diffInDays($endDate) - $startDate->diffInWeeks($endDate) - $nationalHoliday;
            $totalNotCheckIn = 0;
            $totalNotCheckOut = 0;
            $totalPresent = 0;

            foreach ($user->attendancesSummary as $attendance) {
                if (empty($attendance->clock_in) && $attendance->clock_out) {
                    $totalNotCheckIn++;
                }

                if ($attendance->date != Carbon::now()->format('Y-m-d')) {
                    if (empty($attendance->clock_out) && $attendance->clock_in) {
                        $totalNotCheckOut++;
                    }
                }

                if ($attendance->clock_in || $attendance->clock_out) {
                    $totalPresent++;
                }

                $userWorktime = WorkTime::where('id', $attendance->work_time_id)->first();
                $totalMinutesLate += $this->calculateLate($userWorktime, $attendance);
            }


            return [
                'id' => $user->id,
                'user_nip' => $user->nip,
                'user_name' => $user->name,
                'role' => $user->roles[0]?->name ?? '',
                'total_minutes_late' => number_format($totalMinutesLate, 1),
                'total_not_check_in' => $totalNotCheckIn,
                'total_not_check_out' => $totalNotCheckOut,
                'total_present' => $totalPresent . '/' . (int)$periodOfWork,
            ];
        });


        $user->setCollection($data);
        return $user;
    }

    public function calculateLate($userWorktime, $attendance): float|int
    {
        $totalMinutesLate = 0;
        $actualCheckIn = Carbon::make($attendance?->clock_in ?? $attendance->date);
        $workDate = $attendance?->date;
        $expectedCheckIn = Carbon::parse("$workDate {$userWorktime?->clock_in}");

        if ($expectedCheckIn->lessThan($actualCheckIn) && $expectedCheckIn->toTimeString() === "00:00:00") {
            $expectedCheckIn->addDays();
        }

        if ($actualCheckIn->greaterThan($expectedCheckIn)) {
            $lateness = $expectedCheckIn->diffInMinutes($actualCheckIn);
            $totalMinutesLate += $lateness;
        }

        return $totalMinutesLate;
    }


    public function filter($startDate, $endDate, $roleId, $branchId): LengthAwarePaginator
    {
        $data = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate ?? $this->financialClosePeriodService->startDate(), $endDate ?? $this->financialClosePeriodService->endDate()]);
            }
        ])->when(!empty($branchId), function ($query) use ($roleId) {
            $query->where(function ($query) use ($roleId) {
                $query->where('branch_id', $branchId ?? null);
            });
        })->when(!empty($roleId), function ($query) use ($roleId) {
            $query->whereHas('roles', function ($query) use ($roleId) {
                $query->where('id', $roleId);
            });
        })->where('active', 1);

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
