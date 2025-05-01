<?php

namespace App\Support\Attendances;

use App\Models\WorkTime;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WorkTimeService
{

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = WorkTime::paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = WorkTime::search($search)->query(function ($query) {
            $query->orderby('name', 'asc');
        })->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $workTime): LengthAwarePaginator
    {
        $data = $workTime->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'clock_in' => $item->clock_in,
                'clock_out' => $item->clock_out,
                'time_to_checkin' => $item->time_to_checkin,
                'end_time_to_checkin' => $item->end_time_to_checkin,
                'time_to_checkout' => $item->time_to_checkout,
                'end_time_to_checkout' => $item->end_time_to_checkout,
                'is_default' => $item->is_default,
            ];
        });

        $workTime->setCollection($data);
        return $workTime;
    }


    public function getWorktimes(Request $request): array
    {
        $search = $request->input('search');
        $workTimes = WorkTime::search($search)->query(callback: static function ($query) {
            $query->orderby('name', 'asc');
        })->get();

        return $workTimes->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }


    public function selectedWorkTime(WorkTime $workTime): array
    {
        return [
            'id' => $workTime->id,
            'name' => $workTime->name
        ];
    }

    public function setGlobalDefaultWorkTime(WorkTime $workTime): void
    {
        DB::transaction(function () use ($workTime) {
            WorkTime::where('is_default', true)->update(['is_default' => false]);
            $workTime->update(['is_default' => true]);
        });
    }
}
