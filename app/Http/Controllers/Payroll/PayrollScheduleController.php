<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\PayrollScheduleRequest;
use App\Models\PayrollSchedule;
use Illuminate\Http\JsonResponse;

class PayrollScheduleController extends Controller
{
    public function data(): JsonResponse
    {
        $payrollSchedule = PayrollSchedule::first();
        return response()->json($payrollSchedule);
    }


    public function store(PayrollScheduleRequest $request): JsonResponse
    {
        $payrollSchedule = PayrollSchedule::first();
        $payrollSchedule->update(['date' => $request->date]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

}
