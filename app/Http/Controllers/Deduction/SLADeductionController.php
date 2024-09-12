<?php

namespace App\Http\Controllers\Deduction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deduction\SlaDeductionRequest;
use App\Models\SLADeduction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SLADeductionController extends Controller
{

    private SLADeduction $slaDeduction;
    private User $user;

    public function __construct()
    {
        $this->slaDeduction = new SLADeduction();
        $this->user = new User();
    }

    public function index(): View
    {
        return view('pages.payroll.deduction.sla.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->slaDeduction->data());
    }


    public function search(): JsonResponse
    {
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
        foreach ($request->technician_id as $technician) {
            SLADeduction::create([
                'date' => $request->date,
                'technician_id' => $technician,
                'kca_id' => $request->user()->id,
                'spk_amount' => $request->spk_amount,
                'total_deduction_amount' => $request->spk_amount * 10000,
            ]);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }


    public function edit(SLADeduction $SLADeduction): JsonResponse
    {
        return response()->json($SLADeduction);
    }

    public function update(SlaDeductionRequest $request, SLADeduction $SLADeduction): JsonResponse
    {
        $data = $request->validated();
        $data['kca_id'] = $request->user()->id;
        $data['total_deduction_amount'] = $request->spk_amount * 10000;

        $SLADeduction->update($data);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
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
