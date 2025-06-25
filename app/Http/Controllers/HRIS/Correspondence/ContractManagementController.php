<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\ContractManagement;
use App\Models\Master\Common\Branch;
use App\Models\User;
use App\Support\User\ContractManagement\ContractManagementService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;

#[AllowDynamicProperties] class ContractManagementController extends Controller
{

    public function __construct()
    {
        $this->contractManagementService = new ContractManagementService();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.manage-users.contract-management.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->contractManagementService->data());
    }


    public function extendContract(User $user): JsonResponse
    {
        $date = Carbon::now();
        ContractManagement::updateOrCreate([
            'user_id' => User::find($user->id)->id,
        ], [
            'contract_number' => $this->contractManagementService->generateContractNumber($date, $user),
            'start_date' => $date->format('Y-m-d'),
            'end_date' => Carbon::parse($date)->addYear(1)->format('Y-m-d'),
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->contractManagementService->search($request));
    }


    public function edit(ContractManagement $contractManagement): JsonResponse
    {
        return response()->json($contractManagement);
    }


    public function update(Request $request, ContractManagement $contractManagement): JsonResponse
    {
        $data = $request->all();
        $contractManagement->update($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->contractManagementService->filter($request));
    }


    public function contractFile(User $user): Response
    {
        $contract = ContractManagement::with('user')
            ->where(
            'user_id',
            $user->id
        )->first();
        $directorRole = User::role('Director')->with('identityInformation', 'branch')->first();
        $branchManagerRole = User::role('Branch Manager')->with('identityInformation')->first();
        $view = view('pages.manage-users.contract-management.contract-file',
            compact('contract', 'directorRole', 'branchManagerRole'));


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


    public function destroy(ContractManagement $contractManagement): JsonResponse
    {
        $contractManagement->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
