<?php

namespace App\Http\Controllers\HRIS\RolePermissions;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Support\User\RoleHierarchy\Service\RoleHierarchyService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

#[AllowDynamicProperties] class RoleHierarchyController extends Controller
{

    public function __construct()
    {
        $this->roleHierarchyService = new RoleHierarchyService();
    }


    public function index(): View
    {
        return view('pages.manage-users.role-hierarchy.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->roleHierarchyService->data());
    }


    public function search(): JsonResponse
    {
        return response()->json($this->roleHierarchyService->search());
    }


    public function store()
    {

    }


    public function edit()
    {

    }


    public function update()
    {

    }

    public function destroy()
    {

    }
}
