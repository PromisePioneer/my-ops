<?php

namespace App\Support\User\LeaveAndPermission;

use AllowDynamicProperties;
use App\Http\Requests\UserProfile\LeaveAndPermissionRequest;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Support\HelperService\HandleFileUploadService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class LeaveAndPermissionService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
        $this->leaveRepository = new LeaveRepository();
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
                ->addDays($this->importantLeavesDays($request))
            : $request->input('end_date');

        LeaveAndPermission::create([
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'user_id' => $request->user_id ?? $request->user()->id,
            'reason' => $request->reason,
            'leaves_status' => $request->leaves_status,
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
            ? Carbon::parse($request->input('start_date'))->addDays($this->importantLeavesDays($request))
            : $request->input('end_date');


        $leaveAndPermission->update([
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'user_id' => $request->user_id ?? $request->user()->id,
            'reason' => $request->reason,
            'leaves_status' => $request->leaves_status,
            'sick_letter' => $request->sick_letter,
        ]);
    }


    public function getUserData(Request $request)
    {
        $search = $request->input('search');
        $users = User::search($search)->query(function ($query) use ($request) {
            $newQuery = $query->where('active', true);
            LeaveSelect2QueryFilter::apply($newQuery, $request);
        })->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        });
    }

    public function getOwnleaves(Request $request): LengthAwarePaginator
    {
        $leaves = LeaveAndPermission::with('accBy', 'user', 'user.userHasArea', 'user.branch', 'user.roles.department')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at')
            ->paginate(self::$perPage);

        return self::formattedData($leaves);
    }


    public function importantLeavesDays(LeaveAndPermissionRequest $request)
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
