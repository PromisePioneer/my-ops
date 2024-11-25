<?php

namespace App\Http\Controllers\Area;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Models\Branch;
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

    public function data(): JsonResponse
    {
        $area = Area::with('branch')->paginate(10);
        return response()->json($area);
    }

    public function search(Request $request)
    {
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
        Area::create($request->validated());
        return response()->json(['message' => 'Data berhasil ditambahkan.']);
    }


    public function edit(Area $area): JsonResponse
    {
        return response()->json($area);
    }

    public function update(AreaRequest $request, Area $area): JsonResponse
    {
        $area->update($request->validated());
        return response()->json(['message' => 'Data berhasil diubah.']);
    }


    public function getBranchData(Request $request): JsonResponse
    {

        $branch = $this->branch->getData($request);
        return response()->json($branch);
    }

    public function selectedBranch(Area $area): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($area->branch_id));
    }

    public function destroy(Request $request, Area $area): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $area->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function detail(Area $area): View
    {
        return view('pages.general-master-data.area.detail.index', compact('area'));
    }


}
