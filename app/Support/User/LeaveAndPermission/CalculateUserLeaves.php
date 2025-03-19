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
        $user = User::where('id', $request->user_id ?? $request->user()->id)->first();
        $joinDate = Carbon::parse($user->join_date);
        $now = Carbon::now();
        $yearsOfService = $joinDate->diffInYears($now);
        $leaveQuota = $this->leaveQuota($yearsOfService);

        return $this->getDiffDays($request, $leaveQuota, $now);
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

    public function getDiffDays(Request $request, int $leaveQuota, Carbon $now): int
    {
        $totalLeaves = LeaveAndPermission::where('user_id', $request->user_id ?? $request->user()->id)
            ->where('confirmation_status', 'Diterima')
            ->get();

        foreach ($totalLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);

            if ($leaveEnd->year < $now->year) {
                continue;
            }

            $getDiffDays = $leaveStart->diffInDays($leaveEnd);
            $leaveQuota -= $getDiffDays + 1;
        }

        return abs($leaveQuota);
    }
}
