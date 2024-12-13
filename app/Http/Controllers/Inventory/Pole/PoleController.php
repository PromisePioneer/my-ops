<?php

namespace App\Http\Controllers\Inventory\Pole;

use AllowDynamicProperties;
use App\Exports\PoleExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PoleRequest;
use App\Imports\PoleImport;
use App\Models\Branch;
use App\Models\Pole;
use App\Service\PoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

#[AllowDynamicProperties] class PoleController extends Controller
{
    public function __construct()
    {
        $this->branch = new Branch();
        $this->poleService = new PoleService();
    }

    public function index(): View
    {
        return view('pages.operational.poles.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->poleService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->poleService->search($request));
    }


    public function create(): View
    {
        return view('pages.operational.poles.create');
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function store(PoleRequest $request): JsonResponse
    {
        Pole::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function selectedBranch(Pole $pole): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($pole->branch_id));
    }


    public function edit(Pole $pole): View
    {
        return view('pages.operational.poles.edit', compact('pole'));
    }

    public function update(PoleRequest $request, Pole $pole): JsonResponse
    {
        $pole->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function destroy(Request $request, Pole $pole): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $pole->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'data berhasil dihapus']);
    }


    public function import(Request $request): JsonResponse
    {
        ini_set('max_execution_time', 180);
        $file = $request->file('file_import');
        Excel::import(new PoleImport(), $file);

        return response()->json(['message' => 'Data berhasil diimport']);
    }


    public function export(Request $request): BinaryFileResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new PoleExport($startDate, $endDate), 'data-tiang.xlsx');
    }
}
