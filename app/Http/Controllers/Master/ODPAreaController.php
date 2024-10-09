<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\ODPAreaRequest;
use App\Imports\ODPAreaImport;
use App\Imports\UserImport;
use App\Models\Branch;
use App\Models\ODPArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ODPAreaController extends Controller
{

    private Branch $branch;

    public function __construct()
    {
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.master.odp-areas.index');
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

    public function import(Request $request): JsonResponse
    {
        ini_set('max_execution_time', 180);
        $file = $request->file('file_import');
        Excel::import(new ODPAreaImport(), $file);

        return response()->json(['message' => 'Data berhasil diimport']);
    }

}
