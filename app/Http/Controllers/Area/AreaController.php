<?php

namespace App\Http\Controllers\Area;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Models\Branch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AreaController extends Controller
{

    public function __construct()
    {
        $this->branch = new Branch();
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
        $area = Area::with('branch')->withCount('areaHasUser')->where(function($query) use($request) {
            if($request->user()->hasRole('Head Engineer')){
                $query->whereHas('areaHasUser.user', function ($query) use($request) {
                    $query->where('user_id', $request->user()->id);
                });
            }

            if ($request->user()->hasRole('Branch Manager')) {
                $query->where('branch_id', $request->user()->branch_id);
            }
         })->paginate(10);
        return response()->json($area);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Area::class);
        $search = $request->input('search');
        $area = Area::with('branch')->when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('branch', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        })->paginate(10);

        return response()->json($area);
    }


    public function store(AreaRequest $request): JsonResponse
    {
        $this->authorize('create', Area::class);
        Area::create($request->validated());
        return response()->json(['message' => 'Data berhasil ditambahkan.']);
    }


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
        $area->update($request->validated());
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
