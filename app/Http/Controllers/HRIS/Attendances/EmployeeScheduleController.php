<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeScheduleRequest;
use App\Models\EmployeeSchedule;
use App\Models\WorkTime;
use App\Support\Attendances\EmployeeSchedule\EmployeeScheduleService;
use App\Support\Attendances\NationalHoliday\NationalHolidayService;
use App\Support\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

#[AllowDynamicProperties] class EmployeeScheduleController extends Controller
{
    public function __construct()
    {
        $this->employeeScheduleService = new EmployeeScheduleService();
        $this->workTime = new WorkTime();

    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', EmployeeSchedule::class);
        return view('pages.adms.employee-schedules.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', EmployeeSchedule::class);
        return response()->json($this->employeeScheduleService->data($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getWorkTime(Request $request): JsonResponse
    {
        $this->authorize('view', EmployeeSchedule::class);
        return response()->json($this->workTime->getData($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', EmployeeSchedule::class);
        return response()->json($this->employeeScheduleService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedWorkTime(EmployeeSchedule $employeeSchedule): JsonResponse
    {
        $this->authorize('view', EmployeeSchedule::class);
        return response()->json($this->workTime->getSelectedData($employeeSchedule->work_time_id));
    }

    /**
     * @throws AuthorizationException
     */
    public function getSchedules($date, $absentId): JsonResponse
    {
        $this->authorize('view', EmployeeSchedule::class);
        $employeeSchedules = EmployeeSchedule::whereDate('start_date', $date)->where('employee_id', $absentId)->first();
        return response()->json($employeeSchedules);
    }

    /**
     * @throws AuthorizationException
     */
    public function filterByDate(Request $request): JsonResponse
    {
//        $this->authorize('filterByDate', EmployeeSchedule::class);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);


        return response()->json($this->employeeScheduleService->filterByDate($request, $startDate, $endDate));
    }

    /**
     * @throws Throwable
     */
    public function saveSchedules(EmployeeScheduleRequest $request): JsonResponse
    {
        $this->authorize('create', EmployeeSchedule::class);
        DB::transaction(function () use ($request) {

            $selectedWorkTime = WorkTime::find($request->work_time_id);

            if ($selectedWorkTime->name === 'Malam' || $selectedWorkTime->name === 'Sore' || $selectedWorkTime->name === 'KU Malam' || $selectedWorkTime->name === 'CS Sore') {
                $endDate = Carbon::parse($request->start_date)->addDays();
            } else {
                $endDate = Carbon::parse($request->start_date);
            }

                $this->saveBatchSchedule($request, $selectedWorkTime);
        });


        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    /**
     * @throws AuthorizationException
     */
    public function saveBatchSchedule(EmployeeScheduleRequest $request, $selectedWorkTime): void
    {
        $this->authorize('create', EmployeeSchedule::class);

        $date = Carbon::parse($request->start_date);
        $dayCount = Carbon::make($request->start_date)->copy()->addDays((int)$request->day_count);
        $periods = CarbonPeriod::create($date, $dayCount);

        foreach ($periods as $period) {
            if ($selectedWorkTime->name === 'Malam' || $selectedWorkTime->name === 'Sore' || $selectedWorkTime->name === 'KU Malam' || $selectedWorkTime->name === 'CS Sore') {
                $endDate = Carbon::parse($period->format('Y-m-d'))->addDays();
            } else {
                $endDate = Carbon::parse($period->format('Y-m-d'));
            }

            EmployeeSchedule::updateOrCreate([
                'employee_id' => $request->employee_id,
                'start_date' => $period->format('Y-m-d'),
            ], [
                'end_date' => $endDate->format('Y-m-d'),
                'work_time_id' => $request->work_time_id,
                'status' => $request->status,
            ]);
        }
    }


    public function getNationalHoliday(Request $request, NationalHolidayService $nationalHolidayService, FinancialClosePeriodService $financialClosePeriodService): JsonResponse
    {
        $startDate = Carbon::make($request->start_date) ?? $financialClosePeriodService->startDate();
        $endDate = Carbon::make($request->end_date) ?? $financialClosePeriodService->endDate();

        $nationalDay = $nationalHolidayService->getNationalHoliday($startDate, $endDate);

        return response()->json($nationalDay);
    }


    public function exportToPDF(Request $request, NationalHolidayService $nationalHolidayService, FinancialClosePeriodService $financialClosePeriodService): Response
    {

        $startDate = Carbon::make($request->start_date) ?? $financialClosePeriodService->startDate();
        $endDate = Carbon::make($request->end_date) ?? $financialClosePeriodService->endDate();

        $nationalHoliday = $nationalHolidayService->getNationalHoliday($startDate, $endDate);

        $data = $this->employeeScheduleService->data($request);

        $view = view('pages.adms.employee-schedules.schedules', compact('data', 'nationalHoliday'))->render();


        $pdf = Browsershot::html($view)
            ->addChromiumArguments([
                '--headless',
                '--no-sandbox',
            ])
            ->setDelay(200)
            ->ignoreHttpsErrors()
            ->margins(10, 10, 10, 10)
            ->fullPage()
            ->pdf();


        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="example.jpg',
        ]);
    }
}
