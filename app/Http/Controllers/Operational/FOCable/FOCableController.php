<?php

namespace App\Http\Controllers\Operational\FOCable;

use App\Exports\FOCableExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FoCableRequest;
use App\Imports\FOCableImport;
use App\Models\Branch;
use App\Models\FOCable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FOCableController extends Controller
{

    private Branch $branch;

    public function __construct()
    {
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.operational.fo-cables.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(FOCable::paginate(10));
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function selectedBranchData(FOCable $FOCable): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($FOCable->branch_id));
    }

    public function store(FoCableRequest $request): JsonResponse
    {
        FOCable::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function edit(FOCable $FOCable): JsonResponse
    {
        return response()->json($FOCable);
    }

    public function update(FoCableRequest $request, FOCable $FOCable): JsonResponse
    {
        $FOCable->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function destroy(Request $request, FOCable $FOCable): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $FOCable->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function import(Request $request): JsonResponse
    {
        try {
            ini_set('max_execution_time', 180);
            $file = $request->file('file_import');
            Excel::import(new FOCableImport(), $file);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
        return response()->json(['message' => 'Data berhasil diimport']);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new FOCableExport($startDate, $endDate), 'data-kabel-fo.xlsx');
    }
}
