<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPRequest;
use App\Models\SP;
use App\Models\User;
use App\Service\SpService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SPController extends Controller
{

    public readonly int $perPage;
    private SP $sp;
    private User $user;
    private $spService;

    public function __construct()
    {
        $this->sp = new SP();
        $this->perPage = 10;
        $this->user = new User();
        $this->spService = new SPService();
    }

    public function index(): View
    {
        return view('pages.manage-users.sp.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->sp->getDataWithPagination($this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->sp->searchDataWithPagination($request, $this->perPage));
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }


    public function getUserPICAndLeader(Request $request): JsonResponse
    {
        return response()->json($this->user->getUserPICAndLeader($request));
    }

    public function store(SPRequest $request): JsonResponse
    {
        SP::create([
            'branch_id' => $request->user()->branch_id,
            'user_id' => $request->user_id,
            'sp_number' => $this->spService->generateSpNumber($request),
            'sp_date' => $request->sp_date,
            'sp_type' => $request->sp_type,
            'created_by' => $request->user()->id,
            'reason' => $request->reason,
            'punished_by' => $request->user()->id,
            'description' => $request->description
        ]);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function create(): View
    {
        return view('pages.manage-users.sp.create');
    }

    public function edit(SP $sp): View
    {
        return view('pages.manage-users.sp.edit', compact('sp'));
    }

    public function update(SPRequest $request, SP $sp): JsonResponse
    {
        $sp->update([
            'branch_id' => $request->user()->branch_id,
            'user_id' => $request->user_id,
            'sp_number' => $this->spService->generateSpNumber($request),
            'sp_date' => $request->sp_date,
            'sp_type' => $request->sp_type,
            'created_by' => $request->user()->id,
            'reason' => $request->reason,
            'description' => $request->description
        ]);


        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function destroy(SP $sp): JsonResponse
    {
        $sp->delete();
        return response()->json($sp);
    }

    public function exportToPDF(SP $sp): Response
    {
        $pdf = Pdf::loadView('pages.manage-users.sp.export-pdf', compact('sp'))->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
