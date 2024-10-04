<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ManageUserLeaveAndPermissionRequest;
use App\Models\LeaveAndPermission;
use App\Service\LeaveAndPermission\ManageUserLeaveAndPermissionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageUserLeavesController extends Controller
{
    public readonly int $perPage;

    private ManageUserLeaveAndPermissionService $leaveAndPermissionService;

    public function __construct()
    {
        $this->leaveAndPermissionService = new ManageUserLeaveAndPermissionService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', LeaveAndPermission::class);
        return view('pages.manage-users.leaves.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', LeaveAndPermission::class);
        $query = $this->leaveAndPermissionService->data($request);
        return response()->json($query);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', LeaveAndPermission::class);
        return response()->json($this->leaveAndPermissionService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function detail(LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        $this->authorize('viewDetail', LeaveAndPermission::class);
        return response()->json($leaveAndPermission->with('user')->first());
    }

    /**
     * @throws AuthorizationException
     */
    public function changeStatus(
        ManageUserLeaveAndPermissionRequest $request,
        LeaveAndPermission $leaveAndPermission
    ): JsonResponse {
        $this->authorize('changeStatus', LeaveAndPermission::class);
        $data = $request->validated();
        $data['acc_by'] = $request->user()->id;
        $leaveAndPermission->update($data);

        return response()->json([
            'message' => 'Data berhasil di simpan',
        ]);
    }
}
