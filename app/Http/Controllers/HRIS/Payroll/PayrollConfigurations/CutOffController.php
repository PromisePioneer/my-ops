<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollConfigurations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\CutOffRequest;
use App\Models\CutOffPayrollSetting;
use Illuminate\Http\JsonResponse;

class CutOffController extends Controller
{
    public function data(): JsonResponse
    {
        return response()->json(CutOffPayrollSetting::first());
    }

    public function update(CutOffRequest $request): JsonResponse
    {
        $cutOff = CutOffPayrollSetting::first();
        $cutOff->update([
            'attendance_period_start' => $request->attendance_period_start,
            'attendance_period_end' => $request->attendance_period_end,
            'payroll_period_start' => $request->payroll_period_start,
            'payroll_period_end' => $request->payroll_period_end,
        ]);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
