<?php

namespace App\Service\Attendances;

use App\Models\LeaveAndPermission;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\UserWorkTime;
use App\Models\WorkTime;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AttendancesSummaryService
{

    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function data(): LengthAwarePaginator
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $data = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            },
        ])->paginate(10)->onEachSide(1);

        return self::formattedData($data, $startDate, $endDate);
    }


    public function formattedData(LengthAwarePaginator $user, $startDate, $endDate): LengthAwarePaginator
    {
        $data = $user->getCollection()->map(function ($user) use ($startDate, $endDate) {
            $nationalHoliday = NationalHoliday::whereBetween('date', [$startDate, $endDate])->count();
            $totalMinutesLate = 0;
            $userWorktime = $this->getUserWorktime($user);
            $periodOfWork = $startDate->diffInDays($endDate) - $startDate->diffInWeeks($endDate) - $nationalHoliday;
            $totalNotCheckIn = 0;
            $totalNotCheckOut = 0;
            $totalPresent = 0;

            foreach ($user->attendancesSummary as $attendance) {
                if (empty($attendance->clock_in) && $attendance->clock_out) {
                    $totalNotCheckIn++;
                }

                if (empty($attendance->clock_out) && $attendance->clock_in) {
                    $totalNotCheckOut++;
                }

                if ($attendance->clock_in || $attendance->clock_out) {
                    $totalPresent++;
                }

                $totalMinutesLate += $this->calculateLate($userWorktime, $attendance);
            }


            return [
                'id' => $user->id,
                'user_nip' => $user->nip,
                'user_name' => $user->name,
                'total_minutes_late' => (int)$totalMinutesLate,
                'total_not_check_in' => $totalNotCheckIn,
                'total_not_check_out' => $totalNotCheckOut,
                'total_present' => $totalPresent.'/'.(int)$periodOfWork,
            ];
        });


        $user->setCollection($data);
        return $user;
    }

    public function calculateLate($userWorktime, $attendance): float|int
    {
        $totalMinutesLate = 0;
        $expectedCheckInTime = $userWorktime->clock_in;
        $expectedCheckIn = Carbon::parse($attendance->date)->format(
                'Y-m-d'
            ).' '.$expectedCheckInTime;
        $actualCheckIn = Carbon::parse($attendance->date)->format('Y-m-d').' '.$attendance->clock_in;


        $parseExpectedCheckIn = Carbon::parse($expectedCheckIn);
        $parseActualCheckIn = Carbon::parse($actualCheckIn);

        if ($parseActualCheckIn->greaterThan($parseExpectedCheckIn)) {
            return Carbon::parse($expectedCheckIn)->diffInMinutes($actualCheckIn);
        }


        return $totalMinutesLate;
    }


    public function getUserWorktime($user)
    {
        return WorkTime::whereHas('userWorktime', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first() ?? WorkTime::where('name', 'Default')->first();
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();
        $search = $request->input('search');

        $data = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            },
        ]);


        if (!empty($search)) {
            $data->where('name', 'like', '%'.$search.'%')
                ->orWhere('nip', 'like', '%'.$search.'%');
        }

        $data = $data->paginate(10)->onEachSide(1);


        return self::formattedData($data, $startDate, $endDate);
    }
}
