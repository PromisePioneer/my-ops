<?php

namespace App\Http\Controllers\Master\Operational;

use App\Http\Controllers\Controller;
use App\Http\Requests\JointClosureCodeRequest;
use App\Models\Branch;
use App\Models\JointClosureCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class JointClosureCodeController extends Controller
{

    private Branch $branch;

    public function __construct()
    {
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.operational-master-data.joint-closures-code.index');
    }

    public function data(): JsonResponse
    {
        $data = JointClosureCode::with('branch')->paginate(10);
        return response()->json($data);
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function search(): JsonResponse
    {
        return response()->json();
    }


    public function store(JointClosureCodeRequest $request): JsonResponse
    {
        JointClosureCode::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(JointClosureCode $jointClosureCode): JsonResponse
    {
        return response()->json($jointClosureCode);
    }


    public function selectedBranch(JointClosureCode $jointClosureCode): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($jointClosureCode->branch_id));
    }

    public function update(JointClosureCodeRequest $request, JointClosureCode $jointClosureCode): JsonResponse
    {
        $jointClosureCode->update($request->validated());
        return response()->json(['message' => 'Data berhasil diubah']);
    }

    public function destroy(JointClosureCode $jointClosureCode, Request $request): JsonResponse
    {
        $this->authorize('delete', $jointClosureCode);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $jointClosureCode->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'data berhasil dihapus']);
    }

    public function import(Request $request): JsonResponse
    {
        ini_set('max_execution_time', 180);
        $file = $request->file('file_import');
        Excel::import(new JointClosureCode(), $file);

        return response()->json(['message' => 'Data berhasil diimport']);
    }
}
