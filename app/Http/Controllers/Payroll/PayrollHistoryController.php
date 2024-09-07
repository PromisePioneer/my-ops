<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\GeneratePayroll;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PayrollHistoryController extends Controller
{
    public function index(): View
    {
        return view('pages.payroll.payroll-history.index');
    }

    public function data(): JsonResponse
    {
        $payrollHistory = GeneratePayroll::with('user')->paginate(10)->onEachSide(1);
        return response()->json($payrollHistory);
    }
}
