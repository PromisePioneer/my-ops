<?php

namespace App\Support\Utility\ActivityLog\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use LaravelIdea\Helper\Spatie\Activitylog\Models\_IH_Activity_QB;
use Spatie\Activitylog\Models\Activity;

class ActivityLogRepository
{


    public function getActivities($startDate, $endDate): _IH_Activity_QB|Builder|Activity
    {
        return Activity::query()->whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
    }

    public function getLatestActivities()
    {
        return Activity::query()->latest();
    }

    public function findByCauserId(int $userId)
    {
        return Activity::query()
            ->where('causer_id', $userId)
            ->latest();
    }

}
