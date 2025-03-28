<?php

namespace App\Http\Controllers\UserProfile;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendancesSummaryFilterByDateRequest;
use App\Models\User;
use App\Support\UserProfileService\AttendanceRecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AttendanceRecordController extends Controller
{
    public function __construct()
    {
        $this->attendanceRecordService = new AttendanceRecordService();
    }

    public function index(): View
    {
        return view('pages.utilities.user-profile.attendance-records.index');
    }


    public function data(Request $request): JsonResponse
    {
        $data = $this->attendanceRecordService->data($request);
        return response()->json($data);
    }


    public function filter(AttendancesSummaryFilterByDateRequest $request): JsonResponse
    {
        return response()->json($this->attendanceRecordService->filter($request));
    }
}
