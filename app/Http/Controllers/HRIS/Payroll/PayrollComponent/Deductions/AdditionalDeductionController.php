<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollComponent\Deductions;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalDeductionRequest;
use App\Models\AdditionalDeduction;
use App\Models\User;
use App\Support\UserDeduction\AdditionalDeductionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AdditionalDeductionController extends Controller
{
    public function __construct()
    {
        $this->user = new User();
        $this->additionalDeduction = new AdditionalDeduction();
        $this->additionalDeductionService = new AdditionalDeductionService();
    }


    public function index(): View
    {
        return view('pages.payroll.deduction.additional-deduction.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->additionalDeductionService->data());
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');

        return response()->json($this->additionalDeductionService->search($search));
    }


    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(AdditionalDeduction $additionalDeduction): JsonResponse
    {
        return response()->json($this->user->getSelectedData($additionalDeduction->user_id));
    }


    public function store(AdditionalDeductionRequest $request): JsonResponse
    {
        $data = $request->validated();
        AdditionalDeduction::create($data);
        return response()->json([
            'message' => 'Data berhasil disimpan!',
        ]);
    }


    public function edit(AdditionalDeduction $additionalDeduction): JsonResponse
    {
        return response()->json($additionalDeduction);
    }


    public function update(AdditionalDeductionRequest $request, AdditionalDeduction $additionalDeduction): JsonResponse
    {
        $data = $request->validated();
        $additionalDeduction->update($data);
        return response()->json([
            'message' => 'Data berhasil disimpan!',
        ]);
    }


    public function destroy(Request $request, AdditionalDeduction $additionalDeduction): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $additionalDeduction->whereIn('id', $explodeID)->delete();
        return response()->json([
            'message' => 'Data sukses dihapus.',
        ]);
    }
}
