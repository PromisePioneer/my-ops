<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ManageUserLeaveAndPermissionRequest;
use App\Http\Requests\UserProfile\LeaveAndPermissionRequest;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\Master\Common\Branch;
use App\Models\User;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\User\LeaveAndPermission\CalculateUserLeaves;
use App\Support\User\LeaveAndPermission\LeaveAndPermissionService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class LeaveAndPermissionController extends Controller
{
    public function __construct()
    {
        $this->leaveAndPermissionService = new LeaveAndPermissionService();
        $this->user = new User();
        $this->calculateUserLeaves = new CalculateUserLeaves();
        $this->branch = new Branch();
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
        return response()->json($this->leaveAndPermissionService->getUserData($request));
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->leaveAndPermissionService->filter($request));
    }

    public function selectedUserData(LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        return response()->json($this->user->getSelectedData($leaveAndPermission->user_id));
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
     * @throws AuthorizationException|Throwable
     */
    public function changeStatus(
        ManageUserLeaveAndPermissionRequest $request,
        LeaveAndPermission                  $leaveAndPermission
    ): JsonResponse
    {
        $this->authorize('confirm', $leaveAndPermission);
        DB::transaction(function () use ($request, $leaveAndPermission) {
            $empSchedule = EmployeeSchedule::whereBetween('start_date', [$request->start_date, $request->end_date])
                ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                ->get();

            $period = [];

            foreach ($empSchedule as $schedule) {
                $period = array_merge(
                    $period,
                    CarbonPeriod::create($schedule->start_date, $schedule->end_date)->toArray()
                );
            }

            foreach ($period as $p) {
                EmployeeSchedule::whereBetween('start_date', [$p->format('Y-m-d'), $p->format('Y-m-d')])
                    ->orWhereBetween('end_date', [$p->format('Y-m-d'), $p->format('Y-m-d')])
                    ->delete();
            }

            $data = $request->validated();
            $data['acc_by'] = $request->user()->id;
            $leaveAndPermission->update($data);
        });


        return response()->json([
            'message' => 'Data berhasil di simpan',
        ]);
    }


    public function getTotalLeavesLeft(Request $request): JsonResponse
    {
        return response()->json($this->calculateUserLeaves->calculate($request));
    }

    /**
     * @throws Throwable
     */
    public function store(LeaveAndPermissionRequest $request): JsonResponse
    {
        $this->leaveAndPermissionService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function edit(LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        return response()->json($leaveAndPermission);
    }

    public function update(LeaveAndPermissionRequest $request, LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        $this->leaveAndPermissionService->update($request, $leaveAndPermission);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function destroy(Request $request, LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $leaveAndPermission->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }
}
