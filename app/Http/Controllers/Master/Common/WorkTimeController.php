<?php

namespace App\Http\Controllers\Master\Common;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\WorkTimeRequest;
use App\Models\User;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Repositories\WorkTimeRepository;
use App\Support\Attendances\WorkTime\Service\WorkTimeService;
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
        return view('pages.master.operational.work-time.index');
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
    public function store(WorkTimeRequest $request): JsonResponse
    {
        $this->authorize('create', WorkTime::class);
        $data = $request->validated();
        $data['branch_id'] = $request->branch_id ?? $request->user()->branch_id ?? null;
        WorkTime::create($request->validated());

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }


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


    /**
     * @throws AuthorizationException
     */
    public function setGlobalDefaultWorkTime(WorkTime $workTime): JsonResponse
    {
        $this->authorize('setGlobalDefaultWorkTime', $workTime);
        $this->workTimeService->setGlobalDefaultWorkTime($workTime);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function getWorkTimes(Request $request): JsonResponse
    {
        return response()->json($this->workTimeService->getWorktimes($request));
    }


    public function selectedWorkTime(WorkTime $workTime): array
    {
        return [
            'id' => $workTime->id,
            'name' => "{$workTime->name} ({$workTime->clock_in} - {$workTime->clock_out})",
        ];
    }

    public function showDefaultWorkTime(): string
    {
        return WorkTimeService::getWorkTime(WorkTimeRepository::getDefaultWorkTime());
    }
}
