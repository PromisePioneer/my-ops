<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\UserWorkTimeRequest;
use App\Http\Requests\ADMS\WorkTimeRequest;
use App\Models\User;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTimeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class WorkTimeController extends Controller
{
    public readonly int $perPage;

    public function __construct()
    {
        $this->workTime = new WorkTime();
        $this->user = new User();
        $this->perPage = 10;
        $this->workTimeService = new WorkTimeService();
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
    public function data(): JsonResponse
    {
        $this->authorize('view', WorkTime::class);
        return response()->json($this->workTimeService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', WorkTime::class);
        return response()->json($this->workTimeService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUserData(Request $request): JsonResponse
    {
        $this->authorize('view', User::class);
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
            'message' => 'Data berhasil disimpan',
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
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function assignWorkTime(UserWorkTimeRequest $request, WorkTime $workTime): JsonResponse
    {
        foreach ($request['user_id'] as $userId) {
            UserWorkTime::updateOrCreate([
                'user_id' => $userId,
            ], [
                'work_time_id' => $workTime->id,
            ]);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    /**
     * @throws AuthorizationException
     */

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, WorkTime $workTime): JsonResponse
    {
        $this->authorize('destroy', WorkTime::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $workTime->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'data berhasil dihapus']);
    }


    public function setGlobalDefaultWorkTime(WorkTime $workTime): JsonResponse
    {
        $this->workTimeService->setGlobalDefaultWorkTime($workTime);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function getWorkTimes(Request $request): JsonResponse
    {
        return response()->json($this->workTimeService->getWorktimes($request));
    }


    public function selectedWorkTime(WorkTime $workTime): JsonResponse
    {
        return response()->json($this->workTimeService->selectedWorkTime($workTime));
    }
}
