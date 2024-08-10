<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ManageUserLeaveAndPermissionRequest;
use App\Models\LeaveAndPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageUserLeavesController extends Controller
{
    public readonly int $perPage;

    private LeaveAndPermission $leavesAndPermission;

    public function __construct()
    {
        $this->perPage = 10;
        $this->leavesAndPermission = new LeaveAndPermission();
    }

    public function index(): View
    {
        return view('pages.manage-users.leaves.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->leavesAndPermission->getDataWithPaginationBasedOnBranch($request->user()->branch_id, $this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->leavesAndPermission->searchDataWithPaginationBasedOnBranch($request));
    }

    public function detail(LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        return response()->json($leaveAndPermission->with('user')->first());
    }

    public function changeStatus(ManageUserLeaveAndPermissionRequest $request, LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        $data = $request->validated();
        $data['acc_by'] = $request->user()->id;
        $leaveAndPermission->update($data);

        return response()->json([
            'message' => 'Data berhasil di simpan',
        ]);
    }
}
