<?php

namespace App\Service\Attendances\AttendanceManualRequest;

use AllowDynamicProperties;
use App\Http\Requests\AttendanceManualFormRequest;
use App\Models\AttendanceManualHasAttachment;
use App\Models\AttendanceManualHasUser;
use App\Models\AttendanceManualRequest;
use App\Support\HelperService\HandleFileUploadService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class AttendanceManualRequestService
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    public function data(): LengthAwarePaginator
    {
        $attendanceManualRequest = AttendanceManualRequest::with('user', 'attachments')->paginate(self::$perPage);
        return self::formattedData($attendanceManualRequest);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = AttendanceManualRequest::with('user', 'attachments')->when(!empty($search), function ($query) use ($search) {
            return $query->where('reason', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);

        return self::formattedData($query);
    }


    public function formattedData(LengthAwarePaginator $attendanceManualRequest): LengthAwarePaginator
    {
        $data = $attendanceManualRequest->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->start_date) . ' - ' . formatDate($item->end_date),
                'user_names' => $item->user->map(function ($item) {
                    return [
                        'id' => $item->user->id,
                        'nik' => $item->user->nip,
                        'name' => $item->user->name
                    ];
                }),
                'reason' => $item->reason,
                'attachment' => $item->attachments->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'filename' => $item->attachment
                    ];
                }),
                'status' => $item->status,
            ];
        });

        $attendanceManualRequest->setCollection($data);
        return $attendanceManualRequest;
    }

    /**
     * @throws Throwable
     */
    public function store(AttendanceManualFormRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $dates = explode('to', $request->date);
            $startDate = $dates[0];
            $endDate = $dates[sizeof($dates) - 1];

            $attendanceManualRequest = AttendanceManualRequest::create([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'reason' => $request->reason,
            ]);

            $this->userStore($request, $attendanceManualRequest);
            $this->attachmentStore($request, $attendanceManualRequest);
        });
    }

    public function update(AttendanceManualFormRequest $request, AttendanceManualRequest $attendanceManualRequest): void
    {

        $dates = explode('to', $request->date);
        $startDate = $dates[0];
        $endDate = $dates[sizeof($dates) - 1];

        $attendanceManualRequest->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'work_time_id' => $request->work_time_id,
            'reason' => $request->reason,
        ]);

        AttendanceManualHasUser::where('attendance_manual_id', $attendanceManualRequest->id)->delete();
        $this->userStore($request, $attendanceManualRequest);
        $this->attachmentStore($request, $attendanceManualRequest);
    }


    public function userStore($request, $attendanceManualRequest): void
    {
        foreach ($request->users as $user) {
            AttendanceManualHasUser::create([
                'att_manual_request_id' => $attendanceManualRequest->id,
                'user_id' => $user,
            ]);
        }
    }


    public function attachmentStore($request, $attendanceManualRequest): void
    {
        if ($request->file('attachment')) {

            foreach ($request->file('attachment') as $file) {
                $fileUpload = new AttendanceManualHasAttachment();
                $fileUpload->filename = $file->getClientOriginalName();
                $fileUpload->filepath = $file->store('documents/images/attendance-manual-request', 'public');
                $fileUpload->type = $file->getClientOriginalExtension();

                AttendanceManualHasAttachment::create([
                    'att_manual_request_id' => $attendanceManualRequest->id,
                    'attachment' => $fileUpload->filepath,
                ]);
            }
        }
    }


    public function confirm(Request $request, AttendanceManualRequest $attendanceManualRequest): void
    {
        $attendanceManualRequest->update([
            'status' => $request->status
        ]);
    }
}
