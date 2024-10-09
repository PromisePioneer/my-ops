<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\JointClosureAreaRequest;
use App\Models\Branch;
use App\Models\JointClosureArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JointClosureAreaController extends Controller
{

    private Branch $branch;

    public function __construct()
    {
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.master.joint-closures-area.index');
    }

    public function data(): JsonResponse
    {
        $data = JointClosureArea::with('branch')->paginate(10);
        return response()->json($data);
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function search(): JsonResponse
    {
    }


    public function store(JointClosureAreaRequest $request): JsonResponse
    {
        JointClosureArea::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(JointClosureArea $jointClosureArea): JsonResponse
    {
        return response()->json($jointClosureArea);
    }


    public function selectedBranch(JointClosureArea $jointClosureArea): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($jointClosureArea->branch_id));
    }

    public function update(JointClosureAreaRequest $request, JointClosureArea $jointClosureArea): JsonResponse
    {
        $jointClosureArea->update($request->validated());
        return response()->json(['message' => 'Data berhasil diubah']);
    }

    public function destroy(JointClosureArea $jointClosureArea, Request $request): JsonResponse
    {
        $this->authorize('delete', $jointClosureArea);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $jointClosureArea->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'data berhasil dihapus']);
    }
}
