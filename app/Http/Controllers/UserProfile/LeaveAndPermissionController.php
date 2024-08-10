<?php

namespace App\Http\Controllers\UserProfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfile\LeaveAndPermissionRequest;
use App\Models\LeaveAndPermission;
use App\Service\CalculateUserLeaves;
use App\Service\HandleFileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveAndPermissionController extends Controller
{
    public readonly int $perPage;

    private LeaveAndPermission $leavesAndPermission;

    private HandleFileUploadService $handleUploadFileService;

    private CalculateUserLeaves $calculateUserLeaves;

    public function __construct()
    {
        $this->leavesAndPermission = new LeaveAndPermission();
        $this->handleUploadFileService = new HandleFileUploadService();
        $this->calculateUserLeaves = new CalculateUserLeaves();
        $this->perPage = 10;
    }

    public function index(Request $request): View
    {
        return view('pages.utilities.user-profile.leaves-and-permission.index');
    }

    public function data(Request $request): JsonResponse
    {
        $totalLeavesAllowance = $this->calculateUserLeaves->calculate($request);
        $leaves = $this->leavesAndPermission->getDataWithPagination($request->user()->id, $this->perPage);

        return response()->json([
            'totalLeavesAllowance' => $totalLeavesAllowance,
            'leaves' => $leaves,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->leavesAndPermission->searchDataBasedOnUserId($request));
    }

    public function store(LeaveAndPermissionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['file'] = $this->handleUploadFileService->upload($request, 'documents/leave-and-permission/sick-letter',
            'sick_letter');


        LeaveAndPermission::create($data);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function create(): View
    {
        return view('pages.utilities.user-profile.leaves-and-permission.create');
    }

    public function edit(LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        return response()->json($leaveAndPermission);
    }

    public function update(LeaveAndPermissionRequest $request, LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        $leaveAndPermission->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(LeaveAndPermission $leaveAndPermission): JsonResponse
    {
        return response()->json($leaveAndPermission->delete());
    }
}
