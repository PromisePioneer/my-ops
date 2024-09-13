<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\MealAllowanceRequest;
use App\Models\Attendances;
use App\Models\CutOffPayrollSetting;
use App\Models\User;
use App\Models\UserHasMealAllowance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealAllowanceController extends Controller
{

    private UserHasMealAllowance $userHasMealAllowance;
    private User $user;

    public function __construct()
    {
        $this->userHasMealAllowance = new UserHasMealAllowance();
        $this->user = new User();
    }


    public function getUser(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function selectedUser(UserHasMealAllowance $userHasMealAllowance): JsonResponse
    {
        return response()->json($this->user->getSelectedData($userHasMealAllowance->user_id));
    }

    public function index(): View
    {
        return view('pages.payroll.allowances.meal.index');
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = $this->user->data();


        if (!empty($search)) {
            $query->whereHas('role', function ($query) use ($search) {
                $query->where('name', 'like', "%".$search."%");
            })->where('name', 'like', "%".$search."%")
                ->orWhere('amount', 'like', "%".$search."%");
        }

        $data = $query->paginate(10);
        return response()->json($data);
    }

    public function data(): JsonResponse
    {
        return response()->json($this->userHasMealAllowance->data());
    }

    public function store(MealAllowanceRequest $request): JsonResponse
    {
        $attendancePeriodStart = CutOffPayrollSetting::first()->attendance_period_start;
        $attendancePeriodEnd = CutOffPayrollSetting::first()->attendance_period_end;

        $periodStart = Carbon::parse(Carbon::now()->year.'-'.Carbon::now()->subMonth(1)->month.'-'.$attendancePeriodStart);
        $periodEnd = Carbon::parse(Carbon::now()->year.'-'.Carbon::now()->month.'-'.$attendancePeriodEnd);

        $targetedUser = User::where('id', $request->input('user_id'))->first();


        if ($request->type === 'Sesuai Kehadiran') {
            $attendances = Attendances::join('users', 'users.absent_id', '=', 'attendances.employee_id')
                ->where('users.absent_id', $targetedUser->absent_id)
                ->whereBetween('attendances.timestamp', [$periodStart, $periodEnd])
                ->whereNotNull('attendances.status1')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->timestamp)->format('Y-m-d');
                });


            $totalPresent = 0;

            foreach ($attendances as $attendance) {
                $checkIn = $attendance->where('status1', 0)->first();
                $checkOut = $attendance->where('status1', 1)->first();


                if ($checkIn && $checkOut) {
                    $totalPresent++;
                }
            }


            UserHasMealAllowance::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'amount' => 10000 * $totalPresent,
            ]);
        } else {
            UserHasMealAllowance::create([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'type' => $request->type,
                'amount' => $request->amount,
            ]);
        }

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function edit(UserHasMealAllowance $userHasMealAllowance): JsonResponse
    {
        return response()->json($userHasMealAllowance);
    }


    public function update(
        MealAllowanceRequest $request,
        UserHasMealAllowance $userHasMealAllowance
    ): JsonResponse {
        $attendancePeriodStart = CutOffPayrollSetting::first()->attendance_period_start;
        $attendancePeriodEnd = CutOffPayrollSetting::first()->attendance_period_end;

        $periodStart = Carbon::parse(Carbon::now()->year.'-'.Carbon::now()->subMonth(1)->month.'-'.$attendancePeriodStart);
        $periodEnd = Carbon::parse(Carbon::now()->year.'-'.Carbon::now()->month.'-'.$attendancePeriodEnd);
        $targetedUser = User::where('id', $request->input('user_id'))->first();


        if ($request->type === 'Sesuai Kehadiran') {
            $attendances = Attendances::join('users', 'users.absent_id', '=', 'attendances.employee_id')
                ->where('users.absent_id', $targetedUser->absent_id)
                ->whereBetween('attendances.timestamp', [$periodStart, $periodEnd])
                ->whereNotNull('attendances.status1')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->timestamp)->format('Y-m-d');
                });


            $totalPresent = 0;

            foreach ($attendances as $attendance) {
                $checkIn = $attendance->where('status1', 0)->first();
                $checkOut = $attendance->where('status1', 1)->first();


                if ($checkIn && $checkOut) {
                    $totalPresent++;
                }
            }

            $userHasMealAllowance->update([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'amount' => 10000 * $totalPresent,
            ]);
        } else {
            $userHasMealAllowance->update([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'type' => $request->type,
                'amount' => $request->amount,
            ]);
        }

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function destroy(
        Request $request,
        UserHasMealAllowance $userHasMealAllowance
    ): JsonResponse {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $userHasMealAllowance->whereIn('id', $explodeID)->delete();


        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
