<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\SPRequest;
use App\Models\Master\Common\Branch;
use App\Models\SP;
use App\Models\User;
use App\Support\User\SP\SPService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

#[AllowDynamicProperties] class SPController extends Controller
{
    public readonly int $perPage;

    public function __construct()
    {
        $this->SPService = new SPService();
        $this->user = new User();
        $this->branch = new Branch();
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
        return response()->json($this->SPService->data($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', SP::class);
        return response()->json($this->SPService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUserData(Request $request): JsonResponse
    {
        $this->authorize('create', SP::class);
        return response()->json($this->SPService->getEmployeeData($request));
    }

    public function filter(Request $request)
    {
        $branchId = $request->branch_id;
        $year = $request->year;
        $month = $request->month;

        return response()->json($this->SPService->filter($request, $branchId, $year, $month));
    }

    /**
     * @throws AuthorizationException
     */
    public function getSPPIC(Request $request): JsonResponse
    {
        $this->authorize('create', SP::class);
        return response()->json($this->SPService->getSPPic($request));
    }

    /**
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function store(SPRequest $request): JsonResponse
    {
        $this->SPService->store($request);

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
        $this->SPService->update($request, $sp);

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
        $this->authorize('update', $sp);
        return response()->json($this->user->getSelectedData($sp->user_id));
    }

    public function selectedPunishedBy(Sp $sp): JsonResponse
    {
        $this->authorize('update', $sp);
        return response()->json($this->user->getSelectedData($sp->punished_by));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(SP $sp): View
    {
        $this->authorize('update', $sp);
        return view('pages.manage-users.sp.edit', compact('sp'));
    }

    public function getListOfReason(SP $sp): JsonResponse
    {
        $this->authorize('update', $sp);
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

    /**
     * @throws AuthorizationException
     */
    public function exportToPDF(SP $sp): Response
    {
        $this->authorize('viewDetail', $sp);
        $operationalManager = User::role('Operational Manager')->with('roles')->first();

        $spReasonList = json_decode($sp?->list_of_reason);

        $view = view('pages.manage-users.sp.export-pdf',
            compact('sp', 'operationalManager', 'spReasonList'));


        $pdf = Browsershot::html($view)
            ->setOption('executablePath', env('BROWSERSHOT_CHROME_PATH'))
            ->addChromiumArguments([
                'headless',
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-crash-reporter',
                'disable-gpu',
                'disable-software-rasterizer',
                'disable-background-networking',
                'disable-dev-shm-usage',
                'disable-extensions'
            ])
            ->setDelay(200)
            ->ignoreHttpsErrors()
            ->format('A4')
            ->setEnvironmentOptions([
                'CHROME_CONFIG_HOME' => php_uname('s') === 'Windows NT'
                    ? storage_path('app\\chrome\\config') // Path Windows
                    : storage_path('app/chrome/.config')  // Path Linux/Mac
            ])->pdf();


        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="example.pdf',
        ]);
    }

    public function show(SP $sp): JsonResponse
    {
        return response()->json($this->SPService->showSPDetail($sp));
    }
}
