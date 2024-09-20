<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\UserHasOvertimeRequest;
use App\Models\User;
use App\Models\UserHasOvertime;
use App\Service\UserAllowance\OvertimeAllowanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OvertimeAllowanceController extends Controller
{
    private UserHasOvertime $userHasOvertime;
    private User $user;
    private OvertimeAllowanceService $overtimeAllowanceService;

    public function __construct()
    {
        $this->userHasOvertime = new UserHasOvertime();
        $this->user = new User();
        $this->overtimeAllowanceService = new OvertimeAllowanceService();
    }

    public function index(): View
    {
        return view('pages.payroll.allowances.overtime.index');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->userHasOvertime->search($request));
    }

    public function data(): JsonResponse
    {
        return response()->json($this->userHasOvertime->data());
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(UserHasOvertime $userHasOvertime): JsonResponse
    {
        return response()->json($this->user->getSelectedData($userHasOvertime->user_id));
    }

    public function store(UserHasOvertimeRequest $request): JsonResponse
    {
        $this->overtimeAllowanceService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(UserHasOvertime $userHasOvertime): JsonResponse
    {
        return response()->json($userHasOvertime);
    }

    public function update(UserHasOvertimeRequest $request, UserHasOvertime $userHasOvertime): JsonResponse
    {
        $this->overtimeAllowanceService->update($request, $userHasOvertime);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(Request $request, UserHasOvertime $userHasOvertime): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $userHasOvertime->whereIn('id', $explodeID)->delete();


        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
