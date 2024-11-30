<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
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


    public function query()
    {
        return User::with('roles', 'userHasArea')->orderBy('name');
    }

    public function data(Request $request)
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $user = $this->query();

        if ($request->user()->hasAnyRole('NOC Supervisor', 'NOC Staff')) {
            $user->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->whereNull('branch_id');
        }

        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner')) {
            $user->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('Head Engineer', 'Senior Engineer')) {
            $user->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id)
                    ->where('active', 1);
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);;
            });
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1]);
            });
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            })->whereNull('branch_id');
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $user->where('branch_id', $request->user()->branch_id)->paginate(self::$perPage);
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }


        $data = $user->paginate(self::$perPage);

        return self::formattedData($data, $startDate, $endDate);
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $user = $this->query();


        if ($request->user()->hasAnyRole('NOC Supervisor', 'NOC Staff')) {
            $user->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->whereNull('branch_id');
        }

        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner')) {
            $user->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('Head Engineer', 'Senior Engineer')) {
            $user->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id)
                    ->where('active', 1);
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);;
            });
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff']);
            })->whereNull('branch_id');
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            })->whereNull('branch_id');
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $user->where('branch_id', $request->user()->branch_id)->paginate(self::$perPage);
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }


        if (!empty($search)) {
            $user->where('name', 'like', '%' . $search . '%');
        }


        $data = $user->paginate(self::$perPage);


        return self::formattedData($data, $startDate, $endDate);
    }

    public function filterByDate($request, $startDate, $endDate)
    {

        $user = $this->query();

        if ($request->user()->hasAnyRole('NOC Supervisor', 'NOC Staff')) {
            $user->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->whereNull('branch_id')->paginate(self::$perPage);
        }

        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner')) {
            $user->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('Head Engineer', 'Senior Engineer')) {
            $user->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id)
                    ->where('active', 1);
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);;
            })->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff']);
            })->whereNull('branch_id')->paginate(self::$perPage);
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            })->whereNull('branch_id');
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $user->where('branch_id', $request->user()->branch_id)->paginate(self::$perPage);
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }


        $data = $user->paginate(self::$perPage);
        return self::formattedData($data, $startDate, $endDate);
    }


    public function formattedData($userData, $startDate, $endDate)
    {

        $period = CarbonPeriod::create($startDate, $endDate);

        $data = $userData->getCollection()->map(function ($item) use ($startDate, $endDate, $period) {
            $allSchedules = EmployeeSchedule::with('workTime')
                ->whereBetween('date', [$startDate, $endDate])
                ->where('employee_id', $item->absent_id)
                ->get()
                ->keyBy('date');

            $dates = [];
            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $dates[$formattedDate] = [
                    'periodDate' => $formattedDate,
                    'employeeSchedules' => $allSchedules->get($formattedDate),
                ];
            }


            return [
                'id' => $item->id,
                'name' => $item->name,
                'absent_id' => $item->absent_id,
                'date' => collect($dates)->map(function ($date) {
                    return [
                        'period_date' => $date['periodDate'],
                        'schedules_date' => $date['employeeSchedules'],
                        'work_time_schedules' => $date['employeeSchedules']?->workTime?->name .
                            ' (' . $date['employeeSchedules']?->workTime?->clock_in .
                            ' - ' . $date['employeeSchedules']?->workTime?->clock_out . ')',
                    ];
                })->values(),
                'area' => $item->userHasArea?->area,
            ];
        });

        $userData->setCollection($data);
        return $userData;
    }


    public function paginate($items, $perPage = 3, $page = null, $options = []): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
