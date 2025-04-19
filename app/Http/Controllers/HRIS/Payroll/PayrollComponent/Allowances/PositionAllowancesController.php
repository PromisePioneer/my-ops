<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\JobInformation;
use App\Models\User;
use App\Support\UserAllowance\PositionAllowance\PositionAllowanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class PositionAllowancesController extends Controller
{

    public function __construct()
    {
        $this->positionAllowanceService = new PositionAllowanceService();
    }


    public function index(): View
    {
        return view('pages.payroll.allowances.position.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->positionAllowanceService->data());
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->positionAllowanceService->search($request));
    }

    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->positionAllowanceService->filter($request));
    }


    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }


    public function updateOrStore(User $user, Request $request): JsonResponse
    {
        JobInformation::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'position_allowance' => $request->position_allowance
        ]);

        return response()->json(['message' => 'data berhasil disimpan']);
    }

}
