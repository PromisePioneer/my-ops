<?php

namespace App\Http\Controllers\Area;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserHasAreaRequest;
use App\Models\Area;
use App\Models\User;
use App\Models\UserHasArea;
use App\Models\WeekHoliday;
use App\Service\Master\General\Area\AreaDetailService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AreaDetailController extends Controller
{
    public function __construct()
    {
        $this->areaDetailService = new AreaDetailService();
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Area $area): JsonResponse
    {
        $this->authorize('viewDetail', $area);
        return response()->json($this->areaDetailService->data($area));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request, Area $area): JsonResponse
    {
        $this->authorize('viewDetail', $area);
        return response()->json($this->areaDetailService->search($request, $area));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUser(Request $request, Area $area): array
    {
        $this->authorize('createDetail', $area);
        return $this->areaDetailService->getUser($request, $area);
    }


    /**
     * @throws AuthorizationException
     */
    public function assignUser(UserHasAreaRequest $request, Area $area): JsonResponse
    {
        $this->authorize('createDetail', $area);
        UserHasArea::updateOrCreate(
            ['user_id' => $request->user_id],
            ['area_id' => $area->id]);

        return response()->json(['message' => 'Data berhasil disimpan / diubah.']);
    }


    public function assignWeekHoliday(Request $request, User $user): JsonResponse
    {
        WeekHoliday::updateOrCreate(
            ['user_id' => $user->id],
            ['week_holiday' => $request->week_holiday]
        );

        return response()->json([
            'message' => 'Data berhasil disimpan / diubah.',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, UserHasArea $userHasArea): JsonResponse
    {
        $this->authorize('destroyDetail', Area::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $userHasArea->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }
}
