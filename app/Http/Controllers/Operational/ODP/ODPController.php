<?php

namespace App\Http\Controllers\Operational\ODP;

use App\Exports\ODPExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ODPRequest;
use App\Imports\ODPImport;
use App\Models\ODP;
use App\Models\ODPArea;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ODPController extends Controller
{
    private ODPArea $ODPArea;

    public function __construct()
    {
        $this->ODPArea = new ODPArea();
    }


    public function index(): View
    {
        return view('pages.operational.odp.index');
    }


    public function data(): JsonResponse
    {
        return response()->json(ODP::with('area', 'area.branch')->paginate(10));
    }

    public function getODPAreaData(Request $request): JsonResponse
    {
        return response()->json($this->ODPArea->getData($request));
    }


    public function store(ODPRequest $request): JsonResponse
    {
        ODP::create($request->validated());
        return response()->json(['message' => 'Data ODP berhasil ditambahkan.']);
    }


    public function edit(ODP $odp): JsonResponse
    {
        return response()->json($odp);
    }


    public function getSelectedODPArea(ODP $odp): JsonResponse
    {
        return response()->json($this->ODPArea->getSelectedData($odp->area_id));
    }


    public function update(ODPRequest $request, ODP $odp): JsonResponse
    {
        $odp->update($request->validated());
        return response()->json(['message' => 'Data ODP berhasil diubah.']);
    }


    public function destroy(Request $request, ODP $odp): JsonResponse
    {
        $this->authorize('delete', $odp);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $odp->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(Request $request): BinaryFileResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new ODPExport($startDate, $endDate), 'data-.xlsx');
    }


    public function import(Request $request): JsonResponse
    {
        try {
            ini_set('max_execution_time', 180);
            $file = $request->file('file_import');
            Excel::import(new ODPImport(), $file);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
        return response()->json(['message' => 'Data berhasil diimport']);
    }
}
