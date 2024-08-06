<?php

namespace App\Service;

use App\Models\LeaveAndPermission;
use App\Models\UserJobInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalculateUserLeaves
{
    public function calculate(Request $request): int
    {
        $leaveQuota = 0;
        $jobInformation = UserJobInformation::where('user_id', $request->user()->id)->first();
        $joinDate = Carbon::parse($jobInformation->join_date);
        $now = Carbon::now();
        $yearsOfService = $joinDate->diffInYears($now);


        if ($yearsOfService >= 1 && $yearsOfService <= 4) {
            $leaveQuota = 12;
        }

        if ($yearsOfService >= 5) {
            $leaveQuota = 14;
        }


        return $this->getDiffDays($request, $leaveQuota);
    }


    public function getDiffDays(Request $request, int $leaveQuota): int
    {
        $totalLeaves = LeaveAndPermission::where('user_id', $request->user()->id)->where('confirmation_status', 'Diterima')->get();
        foreach ($totalLeaves as $leave) {
            $getDiffDays = Carbon::parse($leave->start_date)->diffInDays($leave->end_date);
            $leaveQuota -= $getDiffDays;
        }

        return $leaveQuota;
    }
}
