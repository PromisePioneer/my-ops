<?php

namespace App\Http\Controllers\Area;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\General\Area\AreaFilterRequest;
use App\Http\Requests\Master\General\Area\AreaRequest;
use App\Models\Area;
use App\Models\Department;
use App\Support\Master\Common\Area\AreaService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AreaController extends Controller
{
    public function __construct()
    {
        $this->areaService = new AreaService();
        $this->department = new Department();
    }

    public function index(): View
    {
        return view('pages.master.common.areas.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', Area::class);
        return response()->json($this->areaService->data($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Area::class);
        return response()->json($this->areaService->search($request));
    }


    public function filter(AreaFilterRequest $request): JsonResponse
    {
        return response()->json($this->areaService->filter($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function store(AreaRequest $request): JsonResponse
    {
        $this->authorize('create', Area::class);
        $this->areaService->store($request);
        return response()->json(['message' => 'Data berhasil ditambahkan.']);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Area $area): JsonResponse
    {
        $this->authorize('update', $area);
        return response()->json($area);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(AreaRequest $request, Area $area): JsonResponse
    {
        $this->authorize('update', $area);
        $this->areaService->update($request, $area);
        return response()->json(['message' => 'Data berhasil diubah.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Area $area): JsonResponse
    {
        $this->authorize('delete', $area);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $area->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    /**
     * @throws AuthorizationException
     */
    public function detail(Area $area): View
    {
        $this->authorize('viewDetail', $area);
        return view('pages.master.common.areas.detail.index', compact('area'));
    }
}
