<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\BaaRequest;
use App\Http\Requests\SPKRequest;
use App\Models\BAA;
use App\Models\Fab;
use App\Models\FabServiceCategory;
use App\Models\SPK;
use App\Models\User;
use App\Support\BAAService;
use App\Support\SPKService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;

#[AllowDynamicProperties] class BAAController extends Controller
{
    public function __construct()
    {
        $this->baaService = new BAAService();
        $this->fab = new Fab();
        $this->spkService = new SpkService();
        $this->user = new User();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('viewAny', BAA::class);
        return view('pages.transaction.baa.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', BAA::class);
        return response()->json($this->baaService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', BAA::class);
        return response()->json($this->baaService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function getFabData(Request $request): JsonResponse
    {
        $this->authorize('view', BAA::class);
        return response()->json($this->fab->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedFabData(BAA $baa): JsonResponse
    {
        $this->authorize('update', $baa);
        return response()->json($this->fab->getSelectedData($baa->fab_id));
    }


    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', BAA::class);
        return view('pages.transaction.baa.create');
    }


    /**
     * @throws AuthorizationException
     */
    public function store(BaaRequest $request): JsonResponse
    {
        $this->authorize('create', BAA::class);
        $this->baaService->store($request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    /**
     * @throws AuthorizationException
     */
    public function detail(BAA $baa): View
    {
        $this->authorize('viewDetail', $baa);
        $getFabServiceCategory = FabServiceCategory::with('service')->where('fab_id', $baa->fab_id)->get();


        $fab = [];
        foreach ($getFabServiceCategory as $fabServiceCategory) {
            $fab [] = $fabServiceCategory->service->name;
        }


        $serviceCategory = implode(',', $fab);

        return view('pages.transaction.baa.detail', compact('baa', 'serviceCategory'));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(BAA $baa): View
    {
        $this->authorize('update', $baa);
        return view('pages.transaction.baa.edit', compact('baa'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(BaaRequest $request, Baa $baa): JsonResponse
    {
        $this->authorize('update', $baa);
        $this->baaService->update($request, $baa);
        return response()->json(['message' => 'data berhasil disimpan']);
    }

    /**
     * @throws AuthorizationException
     */
    public function confirm(BAA $baa): JsonResponse
    {
        $this->authorize('confirm', $baa);
        $baa->update([
            'status' => 1
        ]);

        return response()->json(['message' => 'data berhasil dikonfirmasi']);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(BAA $baa): JsonResponse
    {
        $this->authorize('delete', $baa);
        $baa->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }

    /**
     * @throws AuthorizationException
     */
    public function exportPDF(BAA $baa): Response
    {
        $this->authorize('print', $baa);
        $view = view('pages.transaction.baa.export-pdf', compact('baa'))->render();
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
            'Content-Disposition' => 'inline; filename="' . $baa->baa_number . '".pdf"',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function saveSpk(SPKRequest $request, BAA $baa): JsonResponse
    {
        $this->authorize('createOrUpdateSPK', $baa);
        SPK::updateOrCreate([
            'baa_id' => $baa->id,
        ], [
            'spk_number' => $this->spkService->generateSpkNumber($baa->fab_id),
            'name' => $request->name,
            'date' => $request->date,
            'start_date' => $request->start_date,
            'from' => $baa->fab->pic,
            'to' => $request->to,
            'end_date' => $request->end_date
        ]);

        return response()->json(['message' => 'data berhasil disimpan']);
    }


    /**
     * @throws AuthorizationException
     */
    public function getSpk(BAA $baa): JsonResponse
    {
        $this->authorize('viewDetail', $baa);
        return response()->json(SPK::where('baa_id', $baa->id)->first());
    }

    /**
     * @throws AuthorizationException
     */
    public function getSelectedFrom(User $user): JsonResponse
    {
        $this->authorize('update', BAA::class);
        return response()->json($this->user->getSelectedData($user->id));
    }


    /**
     * @throws AuthorizationException
     */
    public function getSelectedTo(User $user): JsonResponse
    {
        $this->authorize('update', BAA::class);
        return response()->json($this->user->getSelectedData($user->id));
    }

    /**
     * @throws AuthorizationException
     */
    public function exportSpkToPDF(BAA $baa): Response
    {
        $this->authorize('printSPK', $baa);
        $spk = SPK::where('baa_id', $baa->id)->first();
        $view = view('pages.transaction.baa.spk.export-pdf', compact('baa', 'spk'))->render();
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
            'Content-Disposition' => 'inline; filename="' . $spk->spk_number . '".pdf"',
        ]);
    }


    public function getBAA(Request $request): JsonResponse
    {
        return response()->json($this->baaService->getBAA($request));
    }

    public function selectedBAA(BAA $baa): JsonResponse
    {
        return response()->json($this->baaService->selectedBAA($baa));
    }
}
