<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\ThrAllowanceRequest;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\UserHasThrAllowance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ThrAllowancesController extends Controller
{
    private UserHasThrAllowance $userHasThrAllowance;

    public function __construct()
    {
        $this->userHasThrAllowance = new UserHasThrAllowance();
    }


    public function index(): View
    {
        return view('pages.payroll.allowances.thr.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->userHasThrAllowance->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->userHasThrAllowance->search($request));
    }

    public function store(ThrAllowanceRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $userData = User::with('jobInformation')
                ->whereHas('identityInformation', function ($query) use ($request) {
                    $query->where('religion', $request->religion);
                })->get();

            $islamNationalHoliday = NationalHoliday::where(
                'name',
                'like',
                '%'.'Hari Raya Idul Fitri'.'%'
            )->first()->date;


            if ($request->religion === 'Islam') {
            }

            if ($request->religion === 'Kristen') {
            }

            if ($request->religion === 'Hindu') {
            }
            if ($request->religion === 'Buddha') {
            }
            if ($request->religion === 'Konghuchu') {
            }


            $thrPaymentDate = Carbon::parse($islamNationalHoliday)->subDay(7);


            foreach ($userData as $user) {
                $totalThr = 0;

//                $periodOfService = Carbon::parse($user->join_date)->diffInMonths(Carbon::now());
//                $thrMoreThan12Month = (int) $periodOfService >= 12;
//                $thrLessThan12Month = (int) $periodOfService < 12;
//
//                if ($thrMoreThan12Month) {
//                    $totalThr = $user->jobInformation->fixed_salary;
//                }
//
//                if ($thrLessThan12Month) {
//                    $totalThr = (int) $periodOfService / 12 * $user->jobInformation->fixed_salary;
//                }
//
//
//                UserHasThrAllowance::create([
//                    'user_id' => $user->id,
//                    'period' => $request->date,
//                    'amount' => $totalThr,
//                ]);
            }
        });
//
//        return response()->json([
//            'message' => 'data berhasil disimpan',
//        ]);
    }
}
