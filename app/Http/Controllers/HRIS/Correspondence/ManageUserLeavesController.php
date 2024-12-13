<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ManageUserLeaveAndPermissionRequest;
use App\Http\Requests\UserProfile\LeaveAndPermissionRequest;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Service\LeaveAndPermission\CalculateUserLeaves;
use App\Service\LeaveAndPermission\ManageUserLeaveAndPermissionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class ManageUserLeavesController extends Controller
{
    public function __construct()
    {
        $this->manageUserLeaveAndPermissionService = new ManageUserLeaveAndPermissionService();
        $this->user = new User();
        $this->calculateUserLeaves = new CalculateUserLeaves();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', LeaveAndPermission::class);
        return view('pages.manage-users.leaves.index');
    }


    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->manageUserLeaveAndPermissionService->getUserData($request));
    }

    public function selectedUserData(Request $request, LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        return response()->json($this->user->getSelectedData($leaveAndPermission->user_id));
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', LeaveAndPermission::class);
        $query = $this->manageUserLeaveAndPermissionService->data($request);
        return response()->json($query);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', LeaveAndPermission::class);
        return response()->json($this->manageUserLeaveAndPermissionService->search($request));
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
    ): JsonResponse
    {
        $this->authorize('changeStatus', LeaveAndPermission::class);
        $data = $request->validated();
        $data['acc_by'] = $request->user()->id;
        $leaveAndPermission->update($data);

        return response()->json([
            'message' => 'Data berhasil di simpan',
        ]);
    }


    public function getTotalLeavesLeft(Request $request): JsonResponse
    {
        return response()->json($this->calculateUserLeaves->calculate($request));
    }


    public function store(LeaveAndPermissionRequest $request): JsonResponse
    {
        LeaveAndPermission::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => $request->user_id,
            'reason' => $request->reason,
            'leaves_status' => $request->leaves_status,
            'sick_letter' => $request->sick_letter,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function edit(LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        return response()->json($leaveAndPermission);
    }

    public function update(LeaveAndPermissionRequest $request, LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        $leaveAndPermission->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => $request->user_id,
            'reason' => $request->reason,
            'leaves_status' => $request->leaves_status,
            'sick_letter' => $request->sick_letter,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function destroy(Request $request, LeaveAndPermission $leaveAndPermission): JsonResponse
    {
//        $this->authorize('delete', $leaveAndPermission);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $leaveAndPermission->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }
}
