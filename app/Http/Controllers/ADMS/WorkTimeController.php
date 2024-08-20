<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\UserWorkTimeRequest;
use App\Http\Requests\ADMS\WorkTimeRequest;
use App\Models\User;
use App\Models\UserWorkTIme;
use App\Models\WorkTime;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkTimeController extends Controller
{
    public readonly int $perPage;
    private WorkTime $workTime;
    private User $user;
    private UserWorkTIme $userWorkTime;

    public function __construct()
    {
        $this->workTime = new WorkTime();
        $this->user = new User();
        $this->userWorkTime = new UserWorkTIme();
        $this->perPage = 10;
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', WorkTime::class);
        return view('pages.adms.work-time.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', WorkTime::class);
        return response()->json($this->workTime->getDataWithPagination($request->user()->branch_id, $this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->workTime->searchDataWithPagination($request, $this->perPage));
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUserBasedOnBranch($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(WorkTimeRequest $request): JsonResponse
    {
        $this->authorize('create', WorkTime::class);
        $data = $request->validated();
        $data['branch_id'] = $request->user()->branch_id ?? null;
        WorkTime::create($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(WorkTime $workTime): JsonResponse
    {
        $this->authorize('update', $workTime);
        return response()->json($workTime);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(WorkTimeRequest $request, WorkTime $workTime): JsonResponse
    {
        $this->authorize('update', $workTime);
        $workTime->update($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function assignWorkTime(UserWorkTimeRequest $request, WorkTime $workTime): JsonResponse
    {
        foreach ($request['user_id'] as $userId) {
            UserWorkTIme::updateOrCreate([
                'user_id' => $userId,
            ], [
                'work_time_id' => $workTime->id
            ]);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function getSelectedUserWorkTime(WorkTime $workTime): JsonResponse
    {
        return response()->json($this->userWorkTime->getSelectedUserShift($workTime->id));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(
        WorkTime $workTime
    ): JsonResponse {
        $this->authorize('destroy', $workTime);
        $workTime->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }


    public function detail(WorkTime $workTime): View
    {
        return view('pages.adms.work-time.detail', compact('workTime'));
    }

    public function detailData(WorkTime $workTime): JsonResponse
    {
        return response()->json($this->userWorkTime->getDetailUserOnSelectedWorkTime($workTime->id, $this->perPage));
    }
}
