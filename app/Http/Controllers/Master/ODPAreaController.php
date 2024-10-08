<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\ODPAreaRequest;
use App\Models\Branch;
use App\Models\ODPArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ODPAreaController extends Controller
{

    private Branch $branch;

    public function __construct()
    {
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.operational.odp.areas.index');
    }


    public function data(): JsonResponse
    {
        return response()->json(ODPArea::with('branch')->paginate(10));
    }


    public function search(): JsonResponse
    {
        return response()->json();
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function selectedBranchData(ODPArea $odpArea): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($odpArea->branch_id));
    }


    public function store(ODPAreaRequest $request): JsonResponse
    {
        ODPArea::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(ODPArea $odpArea): JsonResponse
    {
        return response()->json($odpArea);
    }


    public function update(ODPAreaRequest $request, ODPArea $odpArea): JsonResponse
    {
        $odpArea->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function destroy(Request $request, ODPArea $odpArea): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $odpArea->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'data berhasil dihapus']);
    }
}
