<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Role;
use App\Models\RoleDefaultWorkTime;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Service\RoleDefaultWorkTimeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class RoleDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->roleDefaultWorkTimeService = new RoleDefaultWorkTimeService();
    }


    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', RoleDefaultWorkTime::class);
        return view('role-default-work-time.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', RoleDefaultWorkTime::class);
        return response()->json($this->roleDefaultWorkTimeService->data());
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', RoleDefaultWorkTime::class);
        return response()->json($this->roleDefaultWorkTimeService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', RoleDefaultWorkTime::class);
        $this->roleDefaultWorkTimeService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(?Role $role, ?WorkTime $workTime): JsonResponse
    {
        $this->authorize('view', RoleDefaultWorkTime::class);
        $roleDefaultWorkTime = RoleDefaultWorkTime::where('role_id', $role->id)->first();
        return response()->json($roleDefaultWorkTime);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Role $role, WorkTime $workTime): JsonResponse
    {
        $this->authorize('reset', RoleDefaultWorkTime::class);
        RoleDefaultWorkTime::where('role_id', $role->id)
            ->where('work_time_id', $workTime->id)
            ->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
