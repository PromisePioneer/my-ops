<?php

namespace App\Support\User\LeaveAndPermission;

use App\Models\LeaveAndPermission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalculateUserLeaves
{
    public function calculate(Request $request, $userId = null): int
    {
        $userId = $request->user_id ?? $request->user()->id;
        $user = User::find($userId);

        if (!$user) {
            return 0;
        }

        $joinDate = Carbon::parse($user->join_date);
        $now = Carbon::now();


        $anniversaryDate = Carbon::parse($now->year . '-' . $joinDate->format('m-d'));


        if ($now->lessThan($anniversaryDate)) {
            $anniversaryDate->subYear();
        }


        $yearsOfService = $joinDate->diffInYears($anniversaryDate);
        $leaveQuota = $this->leaveQuota($yearsOfService);

        return $this->getRemainingLeaves($userId, $leaveQuota, $anniversaryDate, $now);
    }

    public function leaveQuota(int $yearsOfService): int
    {
        if ($yearsOfService >= 1 && $yearsOfService <= 4) {
            return 12;
        }

        if ($yearsOfService >= 5) {
            return 14;
        }

        return 0;
    }

    public function getRemainingLeaves(int $userId, int $leaveQuota, Carbon $anniversaryDate, Carbon $now): int
    {
        $approvedLeaves = LeaveAndPermission::where('user_id', $userId)
            ->where('leaves_status', 'Cuti')
            ->where('confirmation_status', 'Diterima')
            ->where('start_date', '>=', $anniversaryDate->toDateString())
            ->get();

        foreach ($approvedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);

            if ($leaveEnd->year > $now->year) {
                continue;
            }

            $usedDays = $leaveStart->diffInDays($leaveEnd) + 1;
            $leaveQuota -= $usedDays;
        }

        return max(0, $leaveQuota);
    }
}
