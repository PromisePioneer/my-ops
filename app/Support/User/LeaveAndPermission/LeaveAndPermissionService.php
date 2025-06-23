<?php

namespace App\Support\User\LeaveAndPermission;

use AllowDynamicProperties;
use App\Http\Requests\UserProfile\LeaveAndPermissionRequest;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\HelperService\UserSelect2QueryFilter;
use App\Support\User\LeaveAndPermission\Repository\LeaveAndPermissionRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class LeaveAndPermissionService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
        $this->leaveAndPermissionRepository = new LeaveAndPermissionRepository();
        $this->handleFileUploadService = new HandleFileUploadService();
        $this->user = new User();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveAndPermissionRepository->leavesMainQuery();
        $permissions = LeaveACLFilter::apply($query, $request);
        $query = $permissions->paginate(self::$perPage);
        return self::formattedData($query);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveAndPermissionRepository->leavesMainQuery();
        $filter = LeaveQueryFilter::apply($query, $request);
        $permissions = LeaveACLFilter::apply($filter, $request);
        $data = $permissions->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->leaveAndPermissionRepository->leavesMainQuery();
        if (!empty($search)) {
            $query = $this->leaveAndPermissionRepository->searchQuery($query, $search);
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
                'start_date' => formatDate($item->start_date),
                'end_date' => formatDate($item->end_date),
                'leaves_status' => $item->leaves_status,
                'reason' => $item->reason,
                'confirmation_status' => $item->confirmation_status,
                'sick_letter' => $item->sick_letter,
                'important_leaves' => $item->important_leaves,
                'created_at' => $date->format('l, j F Y h:i A'),
            ];
        });

        $data->setCollection($formattedData);

        return $data;
    }


    public function store(LeaveAndPermissionRequest $request): void
    {
        $endDate = $request->input('leaves_status') === 'Cuti Penting'
            ? Carbon::parse($request->input('start_date'))
                ->addDays($this->importantLeavesDays($request) - 1)
            : $request->input('end_date');

        LeaveAndPermission::create([
            'start_date' => $request->input('start_date'),
            'end_date' => $endDate,
            'user_id' => $request->input('user_id') ?? $request->user()->id,
            'reason' => $request->input('reason'),
            'leaves_status' => $request->input('leaves_status'),
            'important_leaves' => $request->input('important_leaves'),
            'sick_letter' => $this->handleFileUploadService->upload(
                $request,
                'documents/leaves-and-permissions/sick-letter',
                'sick_letter'
            ),
        ]);
    }


    public function update(LeaveAndPermissionRequest $request, LeaveAndPermission $leaveAndPermission): void
    {
        $endDate = $request->input('leaves_status') === 'Cuti Penting'
            ? Carbon::parse($request->input('start_date'))->addDays($this->importantLeavesDays($request) - 1)
            : $request->input('end_date');


        $leaveAndPermission->update([
            'start_date' => $request->input('start_date'),
            'end_date' => $endDate,
            'user_id' => $request->user_id ?? $request->user()->id,
            'reason' => $request->input('reason'),
            'leaves_status' => $request->input('leaves_status'),
            'sick_letter' => $this->handleFileUploadService->upload(
                $request,
                'documents/leaves-and-permissions/sick-letter',
                'sick_letter',
                $leaveAndPermission->sick_letter
            ),
        ]);
    }


    public function getUserData(Request $request): Collection
    {
        $search = $request->input('search');
        $users = $this->user->search($search)->query(function ($query) use ($request) {
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
        $leaves = $this->leaveAndPermissionRepository->getOwnLeaves($request)->paginate(self::$perPage);
        return self::formattedData($leaves);
    }


    public function importantLeavesDays(LeaveAndPermissionRequest $request): int
    {
        if ($request->input('important_leaves') === 'Menikah') {
            return 3;
        }

        if ($request->input('important_leaves') === 'Menikahkan Anak'
            ||
            $request->input('important_leaves') === 'Menikahkan Anak'
            ||
            $request->input('important_leaves') === 'Mengkhitankan Anak'
            ||
            $request->input('important_leaves') === 'Membaptis Anak'
            ||
            $request->input('important_leaves') === 'Istri Melahirkan'
            ||
            $request->input('important_leaves') === 'Anggota Keluarga Meninggal Dunia'
        ) {
            return 2;
        }

        return 1;
    }


}
