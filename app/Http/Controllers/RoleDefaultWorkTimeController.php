<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Role;
use App\Models\RoleDefaultWorkTime;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Service\RoleDefaultWorkTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class RoleDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->roleDefaultWorkTimeService = new RoleDefaultWorkTimeService();
    }


    public function index()
    {
        return view('role-default-work-time.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->roleDefaultWorkTimeService->data());
    }


    public function search()
    {

    }

    public function filter()
    {

    }

    public function store(Request $request): JsonResponse
    {
        $this->roleDefaultWorkTimeService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function show(?Role $role, ?WorkTime $workTime): JsonResponse
    {
        $roleDefaultWorkTime = RoleDefaultWorkTime::where('role_id', $role->id)->first();
        return response()->json($roleDefaultWorkTime);
    }


    public function update()
    {

    }


    public function destroy()
    {

    }
}
