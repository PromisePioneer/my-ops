<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\ALlowance\UserHasOvertimeRequest;
use App\Models\CutOffPayrollSetting;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\UserHasOvertime;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OvertimeAllowanceController extends Controller
{
    private UserHasOvertime $userHasOvertime;
    private User $user;

    public function __construct()
    {
        $this->userHasOvertime = new UserHasOvertime();
        $this->user = new User();
    }

    public function index(): View
    {
        return view('pages.payroll.allowances.overtime.index');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->userHasOvertime->search($request));
    }

    public function data(): JsonResponse
    {
        return response()->json($this->userHasOvertime->data());
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(UserHasOvertime $userHasOvertime): JsonResponse
    {
        return response()->json($this->user->getSelectedData($userHasOvertime->user_id));
    }

    public function store(UserHasOvertimeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::with('jobInformation')->where('id', $request->user_id)->first();


        $getPeriodEndMonth = Carbon::now()->addMonth(1)->month;
        $attendancePeriodStart = CutOffPayrollSetting::first()->attendance_period_start;
        $attendancePeriodEnd = CutOffPayrollSetting::first()->attendance_period_end;
        $periodStart = Carbon::parse(Carbon::now()->year.'-'.Carbon::now()->month.'-'.$attendancePeriodStart);
        $periodEnd = Carbon::parse(Carbon::now()->year.'-'.$getPeriodEndMonth.'-'.$attendancePeriodEnd);
        $nationalHoliday = NationalHoliday::whereMonth('date', $periodStart)->count();
        $weekEndCount = $periodStart->diffInWeeks($periodEnd);
        $getPeriodOfWork = $periodStart->diffInDays($periodEnd) - $weekEndCount - $nationalHoliday;


        $data['date'] = Carbon::now();
        $data['amount'] = ($user->jobInformation->fixed_salary / $getPeriodOfWork / 8) * $request->hours;
        UserHasOvertime::create($data);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(UserHasOvertime $userHasOvertime): JsonResponse
    {
        return response()->json($userHasOvertime);
    }

    public function update(UserHasOvertimeRequest $request, UserHasOvertime $userHasOvertime): JsonResponse
    {
        $user = User::with('jobInformation')->where('id', $request->user_id)->first();


        $getPeriodEndMonth = Carbon::now()->addMonth(1)->month;
        $attendancePeriodStart = CutOffPayrollSetting::first()->attendance_period_start;
        $attendancePeriodEnd = CutOffPayrollSetting::first()->attendance_period_end;
        $periodStart = Carbon::parse(Carbon::now()->year.'-'.Carbon::now()->month.'-'.$attendancePeriodStart);
        $periodEnd = Carbon::parse(Carbon::now()->year.'-'.$getPeriodEndMonth.'-'.$attendancePeriodEnd);
        $nationalHoliday = NationalHoliday::whereMonth('date', $periodStart)->count();
        $weekEndCount = $periodStart->diffInWeeks($periodEnd);
        $getPeriodOfWork = $periodStart->diffInDays($periodEnd) - $weekEndCount - $nationalHoliday;

        $data['date'] = Carbon::now();
        $data['amount'] = ($user->jobInformation->fixed_salary / $getPeriodOfWork / 8) * $request->hours;
        $userHasOvertime->update($data);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(Request $request, UserHasOvertime $userHasOvertime): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $userHasOvertime->whereIn('id', $explodeID)->delete();


        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
