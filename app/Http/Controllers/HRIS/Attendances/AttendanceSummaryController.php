<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceCorrectionRequest;
use App\Http\Requests\AttendancesSummaryFilterByDateRequest;
use App\Models\AttendancesSummary;
use App\Models\Branch;
use App\Models\Department;
use App\Models\FpDevice;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\Attendances\AttendancesSummaryService;
use App\Service\Attendances\AttendanceSummaryDetailService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AttendanceSummaryController extends Controller
{
    public function __construct()
    {
        $this->attendanceSummaryService = new AttendancesSummaryService();
        $this->attendanceSummaryDetailService = new AttendanceSummaryDetailService();
        $this->workTime = new WorkTime();
        $this->department = new Department();
        $this->branch = new Branch();
        $this->role = new Role();
        $this->fpDevice = new FpDevice();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', AttendancesSummary::class);
        return view('pages.adms.attendances-summary.index');
    }


    public function data(Request $request): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->data($request));
    }

    public function getDepartmentData(Request $request): JsonResponse
    {
        return response()->json($this->department->getData($request));
    }

    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function getRolesData(Request $request): JsonResponse
    {
        return response()->json($this->role->getData($request));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->search($request));
    }

    public function filterByDate(AttendancesSummaryFilterByDateRequest $request, User $user): JsonResponse
    {
        return response()->json($this->attendanceSummaryDetailService->filterByDate($request, $user));
    }

    public function detail(Request $request, User $user, $startDate = null, $endDate = null): View
    {
        if ($request->user()->hasRole('Branch Manager') && $request->user()->branch_id !== $user->branch_id) {
            abort(403);
        }
        return view('pages.adms.attendances-summary.detail', compact('user', 'startDate', 'endDate'));
    }


    public function detailData(Request $request, User $user, $startDate = null, $endDate = null): JsonResponse
    {
        return response()->json($this->attendanceSummaryDetailService->data($request, $user->absent_id, $startDate, $endDate));
    }


    public function correction($datePeriod, User $user): JsonResponse
    {
        $parseDatePeriod = Carbon::parse($datePeriod)->format('Y-m-d');
        $attendaceVal = AttendancesSummary::whereDate('date', $parseDatePeriod)
            ->where('employee_id', $user->absent_id)
            ->first() ?? $parseDatePeriod;


        return response()->json($attendaceVal);
    }

    public function filter(Request $request): JsonResponse
    {

        $startDate = Carbon::make($request->start_date);
        $endDate = Carbon::make($request->end_date);
        $branchId = $request->branch_id;
        $roleId = $request->role_id;

        return response()->json($this->attendanceSummaryService->filter($startDate, $endDate, $roleId, $branchId));
    }


    public function saveCorrection(AttendanceCorrectionRequest $request, User $user, $datePeriod = null): JsonResponse
    {
        $parseDatePeriod = Carbon::parse($datePeriod)->format('Y-m-d');
        $attendaceVal = AttendancesSummary::where('employee_id', $user->absent_id)
            ->whereDate('date', $parseDatePeriod)->first();


        if ($attendaceVal) {
            $attendaceVal->update([
                'work_time_id' => $request->input('work_time_id'),
                'date' => $request->input('date'),
                'clock_in' => Carbon::make($request->input('clock_in')),
                'clock_out' => Carbon::make($request->input('clock_out')),
            ]);
        } else {
            AttendancesSummary::create([
                'work_time_id' => $request->input('work_time_id'),
                'date' => $request->input('date'),
                'employee_id' => $user->absent_id,
                'clock_in' => Carbon::make($request->input('clock_in')),
                'clock_out' => Carbon::make($request->input('clock_out')),
            ]);
        }

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function getWorkTime(Request $request): JsonResponse
    {
        return response()->json($this->workTime->getData($request));
    }

    public function selectedData(WorkTime $workTime): JsonResponse
    {
        return response()->json($this->workTime->getSelectedData($workTime->id));
    }


    public function absenTanpaMesin(): View
    {
        $users = User::all();
        return view('pages.adms.absen-tanpa-mesin.index', compact('users'));
    }



    public function getFpDevice(Request $request): JsonResponse
    {
        return response()->json($this->fpDevice->getData($request));
    }

}
