<?php

namespace App\Http\Controllers\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Support\VendorPayrollService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

#[AllowDynamicProperties] class VendorPayrollController extends Controller
{

    public function __construct()
    {
        $this->vendorPayrollService = new VendorPayrollService();
    }

    public function index(): View
    {
        return view('pages.payroll.generate-payroll.vendor.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->vendorPayrollService->data());
    }


    public function psbData(): JsonResponse
    {
        return response()->json($this->vendorPayrollService->psbData());
    }
}
