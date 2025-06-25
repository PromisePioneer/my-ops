<?php

namespace App\Support\User\LeaveAndPermission;

use AllowDynamicProperties;
use App\Http\Requests\User\ManageUserLeaveAndPermissionRequest;
use App\Http\Requests\UserProfile\LeaveAndPermissionRequest;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\HelperService\UserSelect2QueryFilter;
use App\Support\User\LeaveAndPermission\Repository\LeaveAndPermissionRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class LeaveAndPermissionService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
        $this->leaveRepository = new LeaveAndPermissionRepository();
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveRepository->leavesMainQuery();
        $permissions = LeaveACLFilter::apply($query, $request);
        $query = $permissions->paginate(self::$perPage);
        return self::formattedData($query);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveRepository->leavesMainQuery();
        $filter = LeaveQueryFilter::apply($query, $request);
        $permissions = LeaveACLFilter::apply($filter, $request);
        $data = $permissions->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->leaveRepository->leavesMainQuery();
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $leaveACLFilter = LeaveACLFilter::apply($query, $request);
        return self::formattedData($leaveACLFilter->paginate(self::$perPage));
    }


    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $formattedData = $data->getCollection()->map(function ($item) {

            $date = Carbon::parse($item->created_at)->locale('id');
            $date->settings(['formatFunction' => 'translatedFormat']);


            return [
                'id' => $item->id,
                'user_id' => $item->user->id,
                'user_name' => '(' . $item->user->nip . ') ' . $item->user->name,
                'start_date' => $item->start_date ? formatDate($item->start_date) : null,
                'end_date' => $item->end_date ? formatDate($item->end_date) : null,
                'leaves_status' => $item->leaves_status,
                'reason' => $item->reason,
                'confirmation_status' => $item->confirmation_status,
                'attachment' => $item->attachment,
                'important_leaves' => $item->important_leaves,
                'created_at' => $date->format('l, j F Y h:i A'),
                'confirmation_reason' => $item->confirmation_reason,
            ];
        });

        $data->setCollection($formattedData);

        return $data;
    }


    public function store(LeaveAndPermissionRequest $request): void
    {

        $endDate = $request->input('end_date');

        if ($request->input('important_leaves') === 'Mendapat Musibah'
            || $request->input('important_leaves') === 'Memenuhi Panggilan Instansi Pemerintah') {
            $endDate = null;
        }

        if ($request->input('leaves_status') === 'Cuti Penting' && $request->input('important_leaves') !== 'Mendapat Musibah'
            && $request->input('important_leaves') !== 'Memenuhi Panggilan Instansi Pemerintah') {
            $endDate = $request->input('end_date') ?: Carbon::parse($request->input('start_date'))
                ->addDays($this->importantLeavesDays($request) - 1);
        }

        LeaveAndPermission::create([
            'start_date' => $request->start_date,
            'end_date' => $endDate ?? $request->input('end_date'),
            'user_id' => $request->user_id ?? $request->user()->id,
            'reason' => $request->reason,
            'leaves_status' => $request->leaves_status,
            'important_leaves' => $request->input('important_leaves'),
            'attachment' => $this->handleFileUploadService->upload(
                $request,
                'documents/leaves-and-permissions/attachment',
                'attachment'
            ),
        ]);


    }


    public function update(LeaveAndPermissionRequest $request, LeaveAndPermission $leaveAndPermission): void
    {
        $endDate = $request->input('end_date');

        if ($request->input('important_leaves') === 'Mendapat Musibah'
            || $request->input('important_leaves') === 'Memenuhi Panggilan Instansi Pemerintah') {
            $endDate = null;
        }

        if ($request->input('leaves_status') === 'Cuti Penting' && $request->input('important_leaves') !== 'Mendapat Musibah'
            && $request->input('important_leaves') !== 'Memenuhi Panggilan Instansi Pemerintah') {
            $endDate = $request->input('end_date') ?: Carbon::parse($request->input('start_date'))
                ->addDays($this->importantLeavesDays($request) - 1);
        }


        $leaveAndPermission->update([
            'start_date' => $request->start_date,
            'end_date' => $endDate ?? $request->input('end_date'),
            'user_id' => $request->user_id ?? $request->user()->id,
            'reason' => $request->reason,
            'leaves_status' => $request->leaves_status,
            'attachment' => $this->handleFileUploadService->upload(
                $request,
                'documents/leaves-and-permissions/attachment',
                'attachment',
                $leaveAndPermission->attachment
            ),
        ]);
    }


    public function getUserData(Request $request)
    {
        $search = $request->input('search');
        $users = User::search($search)->query(function ($query) use ($request) {
            UserSelect2QueryFilter::apply($query, $request);
        })->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        });
    }

    public function getOwnLeaves(Request $request): LengthAwarePaginator
    {
        $leaves = LeaveAndPermission::with('accBy', 'user', 'user.userHasArea', 'user.branch', 'user.roles.department')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at')
            ->paginate(self::$perPage);

        return self::formattedData($leaves);
    }


    /**
     * @throws \Throwable
     */
    public function confirm(
        ManageUserLeaveAndPermissionRequest $request,
        LeaveAndPermission                  $leaveAndPermission
    ): void
    {

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
            if (($leaveAndPermission->important_leaves === 'Mendapat Musibah' || $leaveAndPermission->important_leaves === 'Memenuhi Panggilan Instansi Pemerintah') && ($request->confirmation_status === 'Diterima')) {
                $data['start_date'] = $request->start_date;
                $data['end_date'] = $request->end_date;
            };


            $data['acc_by'] = $request->user()->id;
            $leaveAndPermission->update($data);
        });

    }


    public function importantLeavesDays(LeaveAndPermissionRequest $request): int
    {
        if ($request->input('important_leaves') === 'Menikah'
            || $request->input('important_leaves') === 'Menikahkan Anak'
            || $request->input('important_leaves') === 'Istri Melahirkan'
            || $request->input('important_leaves') === 'Anggota Keluarga Meninggal Dunia'
        ) {
            return 3;
        }

        if ($request->input('important_leaves') === 'Membaptis Anak'
            ||
            $request->input('important_leaves') === 'Mengkhitankan Anak'
        ) {
            return 2;
        }

        return 1;
    }


}
