<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\AttendanceManualFormRequest;
use App\Models\AttendanceManualHasAttachment;
use App\Models\AttendanceManualHasUser;
use App\Models\AttendanceManualRequest;
use App\Models\User;
use App\Service\Attendances\AttendanceManualRequest\AttendanceManualRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

//use App\Models\AttendanceManualAttachment;
//use App\Models\AttendanceManualUser;

#[AllowDynamicProperties] class AttendanceManualRequestController extends Controller
{
    public function __construct()
    {
        $this->attendanceManualRequestService = new AttendanceManualRequestService();
    }


    public function index(): View
    {
        return view('pages.adms.attendance-manual-requests.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->attendanceManualRequestService->data($request));
    }


    public function search(Request $request): JsonResponse
    {

    }


    public function create(): View
    {
        return view('pages.adms.attendance-manual-requests.form');
    }

    /**
     * @throws Throwable
     */
    public function store(AttendanceManualFormRequest $request): JsonResponse
    {
        $this->attendanceManualRequestService->store($request);
        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function getAttachments(AttendanceManualRequest $attendanceManualRequest): JsonResponse
    {
        $attachment = AttendanceManualHasAttachment::where('attendance_manual_id', $attendanceManualRequest->id)->get();
        return response()->json($attachment);
    }

    public function edit(AttendanceManualRequest $attendanceManualRequest): View
    {
        return view('pages.adms.attendance-manual-requests.form', compact('attendanceManualRequest'));
    }


    public function selectedUsers(AttendanceManualRequest $attendanceManualRequest): JsonResponse
    {

        $user = AttendanceManualHasUser::where('attendance_manual_id', $attendanceManualRequest->id)->pluck('user_id');
        $users = User::whereIn('id', $user)->get(['id', 'name']);

        return response()->json($users);
    }

    public function selectedAttachments(AttendanceManualRequest $attendanceManualRequest): JsonResponse
    {
        $attachments = AttendanceManualHasAttachment::where('attendance_manual_id', $attendanceManualRequest->id)->get();
        return response()->json($attachments);
    }

    public function removeAttachment(AttendanceManualHasAttachment $attendanceManualHasAttachment): JsonResponse
    {
        $attendanceManualHasAttachment->delete();
        Storage::disk('public')->delete($attendanceManualHasAttachment->attachment);
        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }


    public function update(AttendanceManualFormRequest $request, AttendanceManualRequest $attendanceManualRequest): JsonResponse
    {
        $this->attendanceManualRequestService->update($request, $attendanceManualRequest);
        return response()->json([
            'message' => 'Data berhasil diupdate',
        ]);
    }


    public function show(AttendanceManualRequest $attendanceManualRequest): JsonResponse
    {
        return response()->json($attendanceManualRequest);
    }

    public function confirm(Request $request, AttendanceManualRequest $attendanceManualRequest): JsonResponse
    {
        $this->attendanceManualRequestService->confirm($request, $attendanceManualRequest);
        return response()->json([
            'message' => 'Data berhasil dikonfirmasi',
        ]);
    }


    public function destroy()
    {

    }
}
