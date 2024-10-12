<?php

namespace App\Http\Controllers\Operational\ODP;

use App\Exports\ODPExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ODPRequest;
use App\Imports\ODPImport;
use App\Models\Branch;
use App\Models\ODP;
use App\Models\ODPArea;
use App\Service\ODPService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ODPController extends Controller
{
    private ODPArea $ODPArea;
    private ODPService $ODPService;

    public function __construct()
    {
        $this->ODPArea = new ODPArea();
        $this->ODPService = new ODPService();
        $this->branch = new Branch();
    }


    public function index(): View
    {
        return view('pages.operational.odp.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->ODPService->data());
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function selectedBranchData(ODP $odp): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($odp->branch_id));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->ODPService->search($request));
    }


    public function create(): View
    {
        return view('pages.operational.odp.create');
    }

    public function store(ODPRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['branch_id'] = $request->branch_id;
        ODP::create($data);
        return response()->json(['message' => 'Data ODP berhasil ditambahkan.']);
    }


    public function edit(ODP $odp): View
    {
        return view('pages.operational.odp.edit', compact('odp'));
    }


    public function getSelectedODPArea(ODP $odp): JsonResponse
    {
        return response()->json($this->ODPArea->getSelectedData($odp->area_id));
    }


    public function update(ODPRequest $request, ODP $odp): JsonResponse
    {
        $data = $request->validated();
        $data['branch_id'] = $request->branch_id;
        $odp->update($data);
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
