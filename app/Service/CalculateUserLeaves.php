<?php

namespace App\Service;

use App\Models\UserJobInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class CalculateUserLeaves
{
    #[NoReturn] public function calculate(Request $request): void
    {
        $totalLeaves = 0;
        $jobInformation = UserJobInformation::where('user_id', $request->user()->id)->first();
        $joinDate = Carbon::parse($jobInformation->join_date);
        $now = Carbon::parse()->now();
        $calculateBetweenNowAndUserJoinDate = $joinDate->diffInYears($now);
        if ($calculateBetweenNowAndUserJoinDate) {
            $totalLeaves += 14;
        }

        dd($totalLeaves);
    }
}
