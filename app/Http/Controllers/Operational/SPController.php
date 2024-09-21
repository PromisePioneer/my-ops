<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPRequest;
use App\Models\SP;
use App\Models\User;
use App\Service\User\SpService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

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
     * @throws Throwable
     */
    public function store(SPRequest $request): JsonResponse
    {
        $currentSP = SP::where('user_id', $request->user_id)
            ->where('expired_if_has_new_sp', false)
            ->where('end_date', '>', Carbon::now())
            ->first();
        $endData = Carbon::parse($request->start_date)->addMonth(6);
        DB::transaction(function () use ($request, $currentSP, $endData) {
            $sp = SP::create([
                'start_date' => $request->start_date,
                'end_date' => $endData,
                'branch_id' => $request->user()->branch_id,
                'user_id' => $request->user_id,
                'sp_number' => $this->spService->generateSpNumber($request),
                'sp_type' => $request->sp_type,
                'created_by' => $request->user()->id,
                'list_of_reason' => json_encode($request['data']),
                'punished_by' => $request->user()->id,
            ]);

            if ($currentSP) {
                $currentSP->expired_if_has_new_sp = true;
                $currentSP->save();
            }

            if ($currentSP?->sp_type === 'SP-3') {
                $user = User::where('id', $sp->user_id)->first();
                $user->active = false;
                $user->save();
            }
        });

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
    public function update(SPRequest $request, SP $sp): JsonResponse
    {
        $sp->update([
            'date' => $request->date,
            'branch_id' => $request->user()->branch_id,
            'user_id' => $request->user_id,
            'sp_number' => $this->spService->generateSpNumber($request),
            'sp_type' => $request->sp_type,
            'created_by' => $request->user()->id,
            'list_of_reason' => json_encode($request['data']),
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function getCurrentSP(User $user): JsonResponse
    {
        $currentSP = SP::where('user_id', $user->id)->where('expired_if_has_new_sp', false)
            ->where('end_date', '>', Carbon::now())
            ->first();

        $resetDate = Carbon::now()->format('Y-m-d');
        $getListOfReasonOfCurrentSP = json_decode($currentSP->list_of_reason);

        return response()->json([
            'current_sp' => $currentSP,
            'list_of_reason' => $getListOfReasonOfCurrentSP,
            'reset_date' => $resetDate,
        ]);
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

    public function getListOfReason(SP $sp): JsonResponse
    {
        $listOfReason = json_decode($sp->list_of_reason);

        return response()->json($listOfReason);
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
        $operationalManager = User::role('Operational Manager')->with('roles')->first();

        $spReasonList = json_decode($sp?->list_of_reason);

        $pdf = Pdf::loadView(
            'pages.manage-users.sp.export-pdf',
            compact('sp', 'punishedBy', 'operationalManager', 'spReasonList')
        )->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->stream();
    }

    public function show(SP $sp): JsonResponse
    {
        return response()->json($this->sp->showSPDetail($sp));
    }
}
