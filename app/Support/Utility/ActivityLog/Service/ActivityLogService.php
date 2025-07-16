<?php

namespace App\Support\Utility\ActivityLog\Service;

use AllowDynamicProperties;
use App\Support\HelperService\FinancialClosePeriodService;
use App\Support\Utility\ActivityLog\Repository\ActivityLogRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

#[AllowDynamicProperties] class ActivityLogService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->activityLogRepository = new ActivityLogRepository();
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->startDate = $this->financialClosePeriodService->startDate();
        $this->endDate = $this->financialClosePeriodService->endDate();
    }


    public function data(): LengthAwarePaginator
    {
        $data = $this->activityLogRepository->getLatestActivities()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function getByCauserId(Request $request): LengthAwarePaginator
    {
        $data = $this->activityLogRepository->findByCauserId($request->user()->id)->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public static function formattedData(LengthAwarePaginator $activity): LengthAwarePaginator
    {
        $data = $activity->getCollection()->map(function ($item) {
            $old = null;
            $attributes = null;

            if (!empty($item->changes['old'])) {
                $old = array_filter($item->changes['old'], static function ($var) {
                    return $var !== null;
                });
            }


            if (!empty($item->changes['attributes'])) {
                $attributes = array_filter($item->changes['attributes'], static function ($var) {
                    return $var !== null;
                });
            }


            return [
                'id' => $item->id,
                'causer' => $item->causer->name,
                'event' => $item->event,
                'description' => $item->description,
                'before' => $old,
                'after' => @$attributes,
                'created_at' => Carbon::parse($item->created_at)
                    ->locale('id')
                    ->settings(['formatFunction' => 'translatedFormat'])
                    ->format('l, j F Y, h:i a'),
            ];
        });

        $activity->setCollection($data);
        return $activity;
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $startDate = Carbon::make($request->start_date) ?? $this->startDate;
        $endDate = Carbon::make($request->end_date) ?? $this->endDate;
        $data = ActivityLogQueryFilter::apply($this->activityLogRepository->getActivities($startDate, $endDate), $request)->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = \App\Models\Activity::search($search)->query(function ($query) use ($search) {
            $query->join('users', 'users.id', '=', 'activity_log.causer_id');
        })->paginate(self::$perPage);

        return self::formattedData($query);
    }

}
