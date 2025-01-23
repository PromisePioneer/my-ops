<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPRequest;
use App\Models\SP;
use App\Models\User;
use App\Service\User\SpService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

class SPController extends Controller
{
    public readonly int $perPage;

    private SP $sp;

    private User $user;

    private SpService $spService;

    public function __construct()
    {
        $this->spService = new SPService();
        $this->user = new User();
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
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', SP::class);
        return response()->json($this->spService->data($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', SP::class);
        return response()->json($this->spService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUserData(Request $request): JsonResponse
    {
        $this->authorize('create', SP::class);
        return response()->json($this->spService->getEmployeeData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getSPPIC(Request $request): JsonResponse
    {
        $this->authorize('create', SP::class);
        return response()->json($this->spService->getSPPic($request));
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

        $endData = Carbon::parse($request->start_date)->addMonths(6);
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
                'punished_by' => $request->punished_by
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
        $this->authorize('create', SP::class);
        return view('pages.manage-users.sp.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function update(SPRequest $request, SP $sp): JsonResponse
    {
        $this->spService->update($request, $sp);

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
        $this->authorize('update', SP::class);
        return response()->json($this->user->getSelectedData($sp->user_id));
    }

    public function selectedPunishedBy(Sp $sp): JsonResponse
    {
        $this->authorize('update', SP::class);
        return response()->json($this->user->getSelectedData($sp->punished_by));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(SP $sp): View
    {
        $this->authorize('update', SP::class);
        return view('pages.manage-users.sp.edit', compact('sp'));
    }

    public function getListOfReason(SP $sp): JsonResponse
    {
        $this->authorize('update', SP::class);
        $listOfReason = json_decode($sp->list_of_reason);
        return response()->json($listOfReason);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(SP $sp): JsonResponse
    {
        $this->authorize('delete', SP::class);
        $sp->delete();

        return response()->json($sp);
    }

    public function exportToPDF(SP $sp): Response
    {
        $punishedBy = User::with('roles')->where('id', $sp->punished_by)->first();
        $operationalManager = User::role('Operational Manager')->with('roles')->first();

        $spReasonList = json_decode($sp?->list_of_reason);

        $view = view('pages.manage-users.sp.export-pdf',
            compact('sp', 'punishedBy', 'operationalManager', 'spReasonList'));


        $pdf = Browsershot::html($view)
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->ignoreHttpsErrors()
            ->format('A4')
            ->setEnvironmentOptions([
                'CHROME_CONFIG_HOME' => storage_path('app/chrome/.config')
            ])->pdf();


        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="example.pdf',
        ]);
    }

    public function show(SP $sp): JsonResponse
    {
        return response()->json($this->spService->showSPDetail($sp));
    }
}
