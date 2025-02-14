<?php

namespace App\Http\Controllers\Area;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Models\Branch;
use App\Models\Department;
use App\Service\GeneralMasterData\Area\AreaService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AreaController extends Controller
{
    public function __construct()
    {
        $this->branch = new Branch();
        $this->areaService = new AreaService();
        $this->department = new Department();
    }

    public function index(): View
    {
        return view('pages.general-master-data.area.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', Area::class);
        return response()->json($this->areaService->data($request));
    }


    public function departmentData(Request $request)
    {
        $search = $request->input('search');
        $department = Department::orderby('name', 'asc')->whereIn('name', ['Vendor', 'Area'])->when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        return $department->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }


    public function selectedDepartment(Area $area): JsonResponse
    {
        return response()->json($this->department->getSelectedData($area->department_id));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Area::class);
        return response()->json($this->areaService->search($request));
    }


    public function filter(Request $request): JsonResponse
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
    public function getBranchData(Request $request): JsonResponse
    {
        $this->authorize('create', Area::class);
        $this->authorize('update', Area::class);
        $branch = $this->branch->getData($request);
        return response()->json($branch);
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedBranch(Area $area): JsonResponse
    {
        $this->authorize('update', $area);
        return response()->json($this->branch->getSelectedData($area->branch_id));
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
        return view('pages.general-master-data.area.detail.index', compact('area'));
    }
}
