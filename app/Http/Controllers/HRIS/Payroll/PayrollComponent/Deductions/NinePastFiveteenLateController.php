<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollComponent\Deductions;

use App\Http\Controllers\Controller;
use App\Http\Requests\NinePastFiveTeenLateDeductionRequest;
use App\Models\NinePastFiveTeenLateDeduction;
use App\Models\User;
use App\Service\UserDeduction\NinePastFiveteenLateDeductionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NinePastFiveteenLateController extends Controller
{
    private User $user;
    private NinePastFiveteenLateDeductionService $ninePastFiveteenLateDeductionService;

    public function __construct()
    {
        $this->user = new User();
        $this->ninePastFiveteenLateDeductionService = new NinePastFiveteenLateDeductionService();
    }

    public function index(): View
    {
        return view('pages.payroll.deduction.nine-past-fiveteen-late.index');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->ninePastFiveteenLateDeductionService->search($request));
    }


    public function data(): JsonResponse
    {
        return response()->json($this->ninePastFiveteenLateDeductionService->data());
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }


    public function getSelectedUser(NinePastFiveTeenLateDeduction $ninePastFiveTeenLateDeduction): JsonResponse
    {
        return response()->json($this->user->getSelectedData($ninePastFiveTeenLateDeduction->technician_id));
    }


    public function store(NinePastFiveTeenLateDeductionRequest $request): JsonResponse
    {
        $this->ninePastFiveteenLateDeductionService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function edit(NinePastFiveTeenLateDeduction $ninePastFiveTeenLateDeduction): JsonResponse
    {
        return response()->json($ninePastFiveTeenLateDeduction);
    }


    public function update(
        NinePastFiveTeenLateDeductionRequest $request,
        NinePastFiveTeenLateDeduction $ninePastFiveTeenLateDeduction
    ): JsonResponse {
        $this->ninePastFiveteenLateDeductionService->update($request, $ninePastFiveTeenLateDeduction);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function destroy(
        Request $request,
        NinePastFiveTeenLateDeduction $ninePastFiveTeenLateDeduction
    ): JsonResponse {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $ninePastFiveTeenLateDeduction->whereIn('id', $explodeID)->delete();
        return response()->json([
            'message' => 'Data sukses dihapus.',
        ]);
    }

}
