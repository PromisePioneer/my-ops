<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollComponent\Deductions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deduction\SlaDeductionRequest;
use App\Models\SLADeduction;
use App\Models\User;
use App\Support\UserDeduction\SLADeductionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SLADeductionController extends Controller
{

    private SLADeduction $slaDeduction;
    private User $user;
    private SLADeductionService $SLADeductionService;

    public function __construct()
    {
        $this->user = new User();
        $this->SLADeductionService = new SLADeductionService();
    }

    public function index(): View
    {
        return view('pages.payroll.deduction.sla.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->SLADeductionService->data());
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->SLADeductionService->search($request));
    }


    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }


    public function getSelectedUser(SLADeduction $SLADeduction): JsonResponse
    {
        return response()->json($this->user->getSelectedData($SLADeduction->technician_id));
    }


    public function store(SlaDeductionRequest $request): JsonResponse
    {
        $this->SLADeductionService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(SLADeduction $SLADeduction): JsonResponse
    {
        return response()->json($SLADeduction);
    }

    public function update(SlaDeductionRequest $request, SLADeduction $SLADeduction): JsonResponse
    {
        $this->SLADeductionService->update($request, $SLADeduction);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function destroy(Request $request, SLADeduction $SLADeduction): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $SLADeduction->whereIn('id', $explodeID)->delete();
        return response()->json([
            'message' => 'Data sukses dihapus.',
        ]);
    }


}
