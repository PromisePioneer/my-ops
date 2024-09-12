<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\PositionAllowanceRequest;
use App\Models\JobInformation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionAllowancesController extends Controller
{
    private User $user;
    private JobInformation $jobInformation;

    public function __construct()
    {
        $this->user = new User();
        $this->jobInformation = new JobInformation();
    }


    public function getUser(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(JobInformation $jobInformation): JsonResponse
    {
        return response()->json($this->user->getSelectedData($jobInformation->user_id));
    }


    public function index(): View
    {
        return view('pages.payroll.allowances.position.index');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->jobInformation->search($request));
    }

    public function data(): JsonResponse
    {
        return response()->json($this->jobInformation->data());
    }

    public function store(PositionAllowanceRequest $request): JsonResponse
    {
        JobInformation::updateOrCreate([
            'user_id' => $request->user_id,
        ], [
            'position_allowance' => $request->position_allowance,
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(JobInformation $jobInformation): JsonResponse
    {
        return response()->json($jobInformation);
    }


    public function update(PositionAllowanceRequest $request, JobInformation $jobInformation): JsonResponse
    {
        $jobInformation->update([
            'user_id' => $request->user_id,
            'position_allowance' => $request->position_allowance,
        ]);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
