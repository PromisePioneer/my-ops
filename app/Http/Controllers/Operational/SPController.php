<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPRequest;
use App\Models\SP;
use App\Models\User;
use App\Service\SpService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SPController extends Controller
{

    public readonly int $perPage;
    private SP $sp;
    private User $user;
    private SpService $spService;

    public function __construct()
    {
        $this->sp = new SP();
        $this->perPage = 10;
        $this->user = new User();
        $this->spService = new SPService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
//        $this->authorize('view', SP::class);
        return view('pages.manage-users.sp.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
//        $this->authorize('view', SP::class);
        return response()->json($this->sp->getDataWithPagination($this->perPage));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
//        $this->authorize('view', SP::class);
        return response()->json($this->sp->searchDataWithPagination($request, $this->perPage));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUserData(Request $request): JsonResponse
    {
//        $this->authorize('create', SP::class);
        return response()->json($this->user->getUser($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(SPRequest $request): JsonResponse
    {
//        $this->authorize('create', SP::class);
        SP::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'branch_id' => $request->user()->branch_id,
            'user_id' => $request->user_id,
            'sp_number' => $this->spService->generateSpNumber($request),
            'sp_type' => $request->sp_type,
            'created_by' => $request->user()->id,
            'reason' => $request->reason,
            'punished_by' => $request->user()->id,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
//        $this->authorize('create', SP::class);
        return view('pages.manage-users.sp.create');
    }

    /**
     * @throws AuthorizationException
     */

    public function selectedUserdata(SP $sp): JsonResponse
    {
//        $this->authorize('update', SP::class);
        return response()->json($this->user->getSelectedData($sp->user_id));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(SP $sp): View
    {
//        $this->authorize('update', SP::class);
        return view('pages.manage-users.sp.edit', compact('sp'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(SPRequest $request, SP $sp): JsonResponse
    {
//        $this->authorize('update', SP::class);
        $sp->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'branch_id' => $request->user()->branch_id,
            'user_id' => $request->user_id,
            'sp_number' => $this->spService->generateSpNumber($request),
            'sp_type' => $request->sp_type,
            'created_by' => $request->user()->id,
            'reason' => $request->reason,
            'description' => $request->description
        ]);


        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(SP $sp): JsonResponse
    {
//        $this->authorize('update', SP::class);
        $sp->delete();
        return response()->json($sp);
    }

    public function exportToPDF(Request $request, SP $sp): Response
    {
        $punishedBy = User::with('roles')->where('id', $sp->punished_by)->first();
        $operationalManager = User::role('Manager Operasional')->with('roles')->first();


        $pdf = Pdf::loadView('pages.manage-users.sp.export-pdf',
            compact('sp', 'punishedBy', 'operationalManager'))->setPaper('A4',
            'portrait');

        return $pdf->stream();
    }
}
