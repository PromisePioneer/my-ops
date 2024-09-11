<?php

namespace App\Http\Controllers\Deduction;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalDeductionRequest;
use App\Models\AdditionalDeduction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdditionalDeductionController extends Controller
{

    private User $user;
    private AdditionalDeduction $additionalDeduction;

    public function __construct()
    {
        $this->user = new User();
        $this->additionalDeduction = new AdditionalDeduction();
    }


    public function index()
    {
        return view('pages.payroll.deduction.additional-deduction.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->additionalDeduction->data());
    }


    public function search()
    {
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
