<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Master\Common\Branch;
use App\Support\Attendances\WorkTime\Service\BranchRoleDefaultWorkTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchRoleDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->branchAndRoleDefaultWorkTimeService = new BranchRoleDefaultWorkTimeService();
    }


    public function index(Branch $branch): View
    {
        return view('pages.adms.work-time-settings.branch-role.index', compact('branch'));
    }


    public function data(Branch $branch): JsonResponse
    {
        return response()->json();
    }


    public function search(Request $request)
    {

    }


    public function filter(Request $request)
    {

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
}
