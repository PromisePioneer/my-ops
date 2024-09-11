<?php

namespace App\Http\Controllers\Deduction;

use App\Http\Controllers\Controller;
use App\Http\Requests\NinePastFiveTeenLateDeductionRequest;
use App\Models\NinePastFiveTeenLateDeduction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NinePastFiveteenLateController extends Controller
{

    private const int AMOUNT_OF_LATE = 10000;
    private NinePastFiveTeenLateDeduction $ninePastFiveTeenLateDeduction;
    private User $user;

    public function __construct()
    {
        $this->ninePastFiveTeenLateDeduction = new NinePastFiveTeenLateDeduction();
        $this->user = new User();
    }

    public function index()
    {
        return view('pages.payroll.deduction.nine-past-fiveteen-late.index');
    }

    public function search()
    {
    }


    public function data(): JsonResponse
    {
        return response()->json($this->ninePastFiveTeenLateDeduction->data());
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }


    public function getSelectedUser(NinePastFiveTeenLateDeduction $ninePastFiveTeenLateDeduction): JsonResponse
    {
        return response()->json($this->user->getSelectedData($ninePastFiveTeenLateDeduction->technician_id));
    }


    public function store(NinePastFiveTeenLateDeductionRequest $request)
    {
        $data = $request->validated();
        $data['kca_id'] = $request->user()->id;
        $data['total_deduction_amount'] = $data['total_amount_of_late'] * self::AMOUNT_OF_LATE;
        NinePastFiveTeenLateDeduction::create($data);

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
        $data = $request->validated();
        $data['total_deduction_amount'] = $data['total_amount_of_late'] * self::AMOUNT_OF_LATE;
        $ninePastFiveTeenLateDeduction->update($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
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
