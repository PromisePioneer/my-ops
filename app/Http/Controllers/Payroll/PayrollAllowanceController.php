<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\PayrollALlowanceRequest;
use App\Models\PayrollAllowance;
use App\Models\RoleHasAllowance;
use Illuminate\Http\JsonResponse;


class PayrollAllowanceController extends Controller
{
    public function data(): JsonResponse
    {
        $payrollAllowances = PayrollAllowance::orderBy('name')->get();
        return response()->json($payrollAllowances);
    }


    public function store(PayrollALlowanceRequest $request): JsonResponse
    {
        $payrollAllowance = PayrollAllowance::create([
            'name' => $request->name,
            'amount' => $request->amount,
        ]);


        foreach ($request->role_id as $value) {
            RoleHasAllowance::create([
                'role_id' => $value,
                'allowance_id' => $payrollAllowance->id,
            ]);
        }
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function destroy(PayrollAllowance $payrollAllowance): JsonResponse
    {
        $payrollAllowance->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }

}
