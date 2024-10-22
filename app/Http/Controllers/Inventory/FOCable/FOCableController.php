<?php

namespace App\Http\Controllers\Inventory\FOCable;

use App\Exports\FOCableExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FoCableRequest;
use App\Imports\FOCableImport;
use App\Models\Branch;
use App\Models\FOCable;
use App\Service\FOCableService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FOCableController extends Controller
{
    private Branch $branch;
    private FoCableService $FOCableService;

    public function __construct()
    {
        $this->branch = new Branch();
        $this->FOCableService = new FOCableService();
    }

    public function index(): View
    {
        return view('pages.operational.fo-cables.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->FOCableService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->FOCableService->search($request));
    }

    public function create(): View
    {
        return view('pages.operational.fo-cables.create');
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

    public function edit(FOCable $FOCable): View
    {
        return view('pages.operational.fo-cables.edit', compact('FOCable'));
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
