<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class EmployeeScheduleService
{

    private static int $perPage = 10;
    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function data()
    {
        $user = User::orderBy('nip')->paginate(self::$perPage);
        return self::formattedData($user);
    }


    public function formattedData($userData)
    {
        $data = $userData->getCollection()->map(function ($item) {

            $startDate = $this->financialClosePeriodService->startDate();
            $endDate = $this->financialClosePeriodService->endDate();

            $period = CarbonPeriod::create($startDate, $endDate);

            $employeeSchedules = EmployeeSchedule::where('user_id', $item->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->paginate(10)
                ->keyBy('date');


            $dates = [];

            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $dates[$formattedDate] = collect([
                    'periodDate' => $formattedDate,
                    'employeeSchedules' => $employeeSchedules->get($formattedDate),
                ]);
            }


            return [
                'id' => $item->id,
                'name' => $item->name,
                'date' => collect($dates)->map(function ($date) {
                    return [
                        'period_date' => $date['periodDate'],
                        'schedules_date' => $date['employeeSchedules'],
                    ];
                })->values()->toArray(),
            ];
        });

        $userData->setCollection($data);
        return $userData;
    }


    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
