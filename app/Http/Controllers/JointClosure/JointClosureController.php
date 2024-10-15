<?php

namespace App\Http\Controllers\JointClosure;

use App\Http\Controllers\Controller;
use App\Http\Requests\JointClosureRequest;
use App\Models\FOCable;
use App\Models\JointClosure;
use App\Models\JointClosureCode;
use App\Service\JointClosureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JointClosureController extends Controller
{


    private JointClosureCode $jointClosureCode;
    private JointClosureService $jointClosureService;
    private FOCable $foCable;

    public function __construct()
    {
        $this->jointClosureService = new JointClosureService();
        $this->jointClosureCode = new JointClosureCode();
        $this->foCable = new FoCable();
    }

    public function index(): View
    {
        return view('pages.operational.joint-closures.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->jointClosureService->data());
    }

    public function create(): View
    {
        return view('pages.operational.joint-closures.create');
    }


    public function getJointClosuresCode(Request $request): JsonResponse
    {
        return response()->json($this->jointClosureCode->getData($request));
    }


    public function getFoCable(Request $request): JsonResponse
    {
        return response()->json($this->foCable->getData($request));
    }


    public function getSelectedCode(JointClosure $jointClosure): JsonResponse
    {
        return response()->json($this->jointClosureCode->getSelectedData($jointClosure->code_id));
    }

    public function getSelectedFoCable(JointClosure $jointClosure): JsonResponse
    {
        return response()->json($this->foCable->getSelectedData($jointClosure->fo_cable_id));
    }


    public function store(JointClosureRequest $request): JsonResponse
    {
        JointClosure::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function edit(JointClosure $jointClosure): JsonResponse
    {
        return response()->json($jointClosure);
    }


    public function update(JointClosureRequest $request, JointClosure $jointClosure): JsonResponse
    {
        $jointClosure->update($request->validated());
        return response()->json(['message' => 'Data berhasil diupdate.']);
    }


    public function destroy(JointClosure $jointClosure, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $jointClosure->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
