<?php

namespace App\Support\UserAllowance;

use App\Http\Requests\Allowances\MealAllowanceRequest;
use App\Models\Attendances;
use App\Models\User;
use App\Models\UserHasMealAllowance;
use App\Support\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;

class MealAllowanceService
{
    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function store(MealAllowanceRequest $request)
    {
        $targetedUser = User::where('id', $request->input('user_id'))->first();
        $attendances = $this->getAttendancesDataInThisMonth($targetedUser);
        $totalPresent = 0;

        foreach ($attendances as $attendance) {
            $checkIn = $attendance->where('status1', 0)->first();
            $checkOut = $attendance->where('status1', 1)->first();

            if ($checkIn && $checkOut) {
                $totalPresent++;
            }
        }


        return UserHasMealAllowance::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'amount' => $request->type === 'Sesuai Kehadiran' ? 10000 * $totalPresent : $request->amount,
        ]);
    }

    public function getAttendancesDataInThisMonth($targetedUser)
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();


        return Attendances::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->where('users.absent_id', $targetedUser->absent_id)
            ->whereBetween('attendances.timestamp', [$startDate, $endDate])
            ->whereNotNull('attendances.status1')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->timestamp)->format('Y-m-d');
            });
    }

    public function update(MealAllowanceRequest $request, UserHasMealAllowance $userHasMealAllowance): bool
    {
        $targetedUser = User::where('id', $request->input('user_id'))->first();
        $attendances = $this->getAttendancesDataInThisMonth($targetedUser);
        $totalPresent = 0;

        foreach ($attendances as $attendance) {
            $checkIn = $attendance->where('status1', 0)->first();
            $checkOut = $attendance->where('status1', 1)->first();

            if ($checkIn && $checkOut) {
                $totalPresent++;
            }
        }

        return $userHasMealAllowance->update([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'amount' => $request->type === 'Sesuai Kehadiran' ? 10000 * $totalPresent : $request->amount,
        ]);
    }

}
