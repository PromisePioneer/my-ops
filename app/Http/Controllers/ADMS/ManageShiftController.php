<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\ManageShiftRequest;
use App\Models\ManageShift;
use Illuminate\Http\JsonResponse;

class ManageShiftController extends Controller
{
    public readonly int $perPage;
    private ManageShift $manageShift;

    public function __construct()
    {
        $this->manageShift = new ManageShift();
        $this->perPage = 10;
    }

    public function index()
    {
        return view('pages.adms.manage-shift.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->manageShift->getDataWithPagination($this->perPage));
    }

    public function search()
    {
    }

    public function store(ManageShiftRequest $request): JsonResponse
    {
        ManageShift::create($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function edit(ManageShift $manageShift): JsonResponse
    {
        return response()->json($manageShift);
    }

    public function update(ManageShiftRequest $request, ManageShift $manageShift): JsonResponse
    {
        $manageShift->update($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function destroy(ManageShift $manageShift): JsonResponse
    {
        $manageShift->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }

}
