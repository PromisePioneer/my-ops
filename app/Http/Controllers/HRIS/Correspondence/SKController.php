<?php

namespace App\Http\Controllers\HRIS\Correspondence;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SKRequest;
use App\Models\Branch;
use App\Models\Role;
use App\Models\SK;
use App\Models\User;
use App\Service\User\SKService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class SKController extends Controller
{

    private SKService $SKService;
    private User $user;
    private Role $role;
    private Branch $branch;

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

    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
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

    public function getSelectedRole(SK $sk): JsonResponse
    {
        return response()->json($this->role->selectedRole($sk->new_role_id));
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
        $operationalManager = User::role('Operational Manager')->first();
        $pdf = Pdf::loadView(
            'pages.manage-users.sk.sk-pdf',
            compact('operationalManager', 'sk')
        )->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
