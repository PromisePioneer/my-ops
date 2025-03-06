<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SKRequest;
use App\Models\Branch;
use App\Models\Role;
use App\Models\SK;
use App\Models\User;
use App\Service\User\SK\SKService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

#[AllowDynamicProperties] class SKController extends Controller
{

    public function __construct()
    {
        $this->SKService = new SKService();
        $this->user = new User();
        $this->role = new Role();
        $this->branch = new Branch();
    }


    public function index(): View
    {
        return view('pages.manage-users.sk.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->SKService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->SKService->search($request));
    }


    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getRoleData(Request $request): JsonResponse
    {
        return response()->json($this->role->getData($request));
    }

    /**
     * @throws Throwable
     */
    public function store(SKRequest $request): JsonResponse
    {
        $this->SKService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function getSelectedUser(SK $sk): JsonResponse
    {
        return response()->json($this->user->getSelectedData($sk->user_id));
    }

    public function getSelectedBranch(SK $sk): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($sk->new_branch_id));
    }

    public function edit(SK $sk): JsonResponse
    {
        return response()->json($sk);
    }


    public function update(SKRequest $request, SK $sk): JsonResponse
    {
        $this->SKService->update($request, $sk);
        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function exportToPDF(SK $sk): Response
    {
        $director = User::role('Director')->first();

        $view = view('pages.manage-users.sk.sk-pdf',
            compact('director', 'sk'));


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
}
