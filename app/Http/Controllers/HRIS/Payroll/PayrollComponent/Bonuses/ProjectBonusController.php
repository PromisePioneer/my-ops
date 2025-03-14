<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollComponent\Bonuses;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectBonusRequest;
use App\Models\ProjectBonus;
use App\Models\User;
use App\Support\ProjectBonusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProjectBonusController extends Controller
{

    private ProjectBonusService $projectBonusService;
    private User $user;

    public function __construct()
    {
        $this->projectBonusService = new ProjectBonusService();
        $this->user = new User();
    }

    public function index(): View
    {
        return view('pages.payroll.benefit.project-bonus.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->projectBonusService->data());
    }


    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(ProjectBonus $projectBonus): JsonResponse
    {
        return response()->json($this->user->getSelectedData($projectBonus->user_id));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->projectBonusService->search($request));
    }


    public function create(): View
    {
        return view('pages.payroll.benefit.project-bonus.create');
    }


    public function store(ProjectBonusRequest $request): JsonResponse
    {
        $this->projectBonusService->store($request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function edit(ProjectBonus $projectBonus): View
    {
        return view('pages.payroll.benefit.project-bonus.edit', compact('projectBonus'));
    }


    public function getUserhasProjectBonus(ProjectBonus $projectBonus): JsonResponse
    {
        return response()->json($this->projectBonusService->getUserhasProjectBonus($projectBonus));
    }


    public function getSelectedProjectBonus(User $projectBonus): JsonResponse
    {
        return response()->json($this->projectBonusService->getSelectedProjectBonus($projectBonus));
    }

    /**
     * @throws Throwable
     */
    public function update(ProjectBonusRequest $request, ProjectBonus $projectBonus): JsonResponse
    {
        $this->projectBonusService->update($request, $projectBonus);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function viewFile(ProjectBonus $projectBonus): View
    {
        return view('pages.payroll.benefit.project-bonus.view-file', compact('projectBonus'));
    }


    public function destroy(Request $request, ProjectBonus $projectBonus): JsonResponse
    {
        return response()->json($this->projectBonusService->destroy($request, $projectBonus));
    }
}
